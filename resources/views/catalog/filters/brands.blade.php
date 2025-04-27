<div>
    <h5 class="mb-4 text-sm 2xl:text-md font-bold">{{$filter->title()}}</h5>
    @foreach ($filter->values() as $id => $label)
        <div class="form-checkbox">
            <input 
                x-on2:change="$refs.filterForm.submit()"
                name="{{$filter->name($id)}}" 
                type="checkbox" 
                id="{{$filter->id($id)}}"
                value="{{$id}}"
                @checked($filter->requestValue($id))
                >
            <label for="{{$filter->id($id)}}" class="form-checkbox-label">{{$label}}</label>
        </div>
    @endforeach
</div>