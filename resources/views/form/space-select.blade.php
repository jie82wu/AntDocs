@foreach($options as $option)
    @include('components.custom-checkbox', [
        'name' => $name,
        'label' => $option->name,
        'value' => $option->id,
        'checked' => isset($spaceIds)&&in_array($option->id,$spaceIds)
    ])
@endforeach
