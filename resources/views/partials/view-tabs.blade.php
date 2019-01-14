<!-- Nav tabs -->
<ul class="nav nav-tabs">
<?php
$tabs = [
    'view' => 'Standard',
    'outline' => 'Outline',
];
if (!empty($concept->config->epub)) {
    $tabs['^reader'] = 'Reader';
}
?>
@foreach ($tabs as $tab => $label)
    <?php
    $target = '';
    if (strpos($tab, '^') === 0) {
        $tab = substr($tab, 1);
        $target = ' target="_blank"';
    }
    ?>
    <li role="presentation" class="nav-item">
        <a{!! $target !!} class="nav-link{{ $active == $tab ? ' active' : '' }}" href="{{$tab}}" role="tab">{{$label}}</a></li>
@endforeach
</ul>