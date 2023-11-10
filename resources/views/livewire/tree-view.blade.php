<div>
    <ul @style([
    'margin-left: 2em' => $isChild,
])>
        @foreach($treeData as $node)
            <li wire:key="root-{{ $node['id'] }}">
                <div class="flex flex-row items-center gap-2">
                    <div class="icon-wrapper">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             aria-hidden="true">
                            <path
                                d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-15a3 3 0 00-3 3V18a3 3 0 003 3h15zM1.5 10.146V6a3 3 0 013-3h5.379a2.25 2.25 0 011.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 013 3v1.146A4.483 4.483 0 0019.5 9h-15a4.483 4.483 0 00-3 1.146z"></path>
                        </svg>
                    </div>
                    <div class="item">{{ $node['name'] }}</div>
                    @if($node['children_count'])
                        <button wire:click="getChildren({{ $node['id'] }})" class="btn btn-xs btn-secondary">
                            @if($node['open'])
                                -
                            @else
                                +
                            @endif
                        </button>
                    @endif
                </div>

                @if(count($node['children']) && $node['open'])
                    <livewire:tree-view :key="$node['id']" :is-child="true" :tree-data="$node['children']"/>
                @endif
            </li>
        @endforeach
    </ul>
</div>
