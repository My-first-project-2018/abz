@if($employees->isNotEmpty())
        <ul class="subordinate ">
                @foreach($employees as $subordinate)
                    <li class="subordinate__item" data-url="{{route('employeeSubordinates',['employee' => $subordinate->hash ])}}">
                        <p class="name">{{$subordinate->first_name.' '.$subordinate->last_name}}</p>
                        <p class="position">{{$subordinate->position->name}}</p>
                        <div class="show_subordinate"><img src="{{asset('img/next.svg')}}" alt=""></div>
                    </li>
                @endforeach
        </ul>
@else
    <p>No records</p>
@endif