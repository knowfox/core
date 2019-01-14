<?php

namespace Knowfox\Core\Services;

use Knowfox\Core\Models\Concept;

class OutlineService
{
    public function render(Concept $concept, $container_view, $outline_view)
    {
        return view($container_view, [
            'concept' => $concept,
            'tree' => view($outline_view, [
                'concept' => $concept,
                'descendants' => $this->traverse($concept, $outline_view),
            ]),
        ]);
    }

    public function renderDescendents(Concept $concept, $container_view, $outline_view)
    {
        return view($container_view, [
            'concept' => $concept,
            'descendants' => $this->traverse($concept, $outline_view),
        ])->render();
    }

    public function traverse(Concept $concept, $outline_view, $callback = null, $preprocess = null, $by_date = false)
    {
        $concept->load('descendants');

        $traverse = function ($tree) use (&$traverse, $outline_view, $callback, $preprocess) {

            $concepts = [];
            foreach ($tree as $concept) {

                if ($preprocess) {
                    call_user_func($preprocess, $concept);
                }

                $concepts[] = (object)[
                    'concept' => $concept,
                    'rendered' => view($outline_view, [
                        'concept' => $concept,
                        'descendants' => call_user_func($traverse, $concept->children),
                    ])->render(),
                ];
            }

            if ($callback) {
                return call_user_func($callback, $concepts);
            }
            else {
                return join("\n", array_map(
                    function ($item) { return $item->rendered; },
                    $concepts
                ));
            }
        };

        if ($by_date) {
            return call_user_func($traverse,
                $concept->descendants()->orderBy('created_at', 'desc')->get()->toTree()
            );
        }
        return call_user_func($traverse, $concept->descendants->toTree());
    }

    private function convertArray(&$ary)
    {
        foreach (array_keys($ary) as $n) {
            foreach (array_keys($ary[$n]) as $key) {
                if ($key == '@outlines') {
                    $ary[$n]['children'] = &$ary[$n]['@outlines'];
                    unset($ary[$n]['@outlines']);

                    $this->convertArray($ary[$n]['children']);
                }
                else
                    if ($key == 'text') {
                        $ary[$n]['title'] = &$ary[$n]['text'];
                        unset($ary[$n]['text']);
                    }
            }
        }
    }

    public function update($concept, $data)
    {
        $this->convertArray($data['body']);

        $tree = Concept::descendantsOf($concept->id)->toFlatTree();

        $count = Concept::whereDescendantOrSelf($concept->parent_id)
            ->rebuildTree([[
                'id' => $concept->parent_id,
                'children' => $data['body'],
            ]], true);

        $fails = [];
        foreach ($tree as $node) {
            if (!$node->isDescendantOf($concept)) {
                $fails[] = $node;
            }
        }

        return [ $count, $fails ];
    }
}
