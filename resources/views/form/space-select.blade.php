@include('components.custom-checkbox', [
    'name' => $name,
    'label' => trans('space.private'),
    'value' => 0,
    'checked' => isset($spaceIds)&&in_array(0,$spaceIds)
])

@foreach($options as $option)
    @include('components.custom-checkbox', [
        'name' => $name,
        'label' => $option->name,
        'value' => $option->id,
        'checked' => isset($spaceIds)&&in_array($option->id,$spaceIds)
    ])
@endforeach
