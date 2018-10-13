
    @if($employees->isNotEmpty())
        @foreach($employees as $employee)
            <div class="employees__item">
                <div class="photo"><img src="img/3.png" alt=""></div>
                <div class="firstName">
                    <span class="employee__block__label">First Name</span>
                    <p>{{$employee->first_name}}</p>
                </div>
                <div class="lastName">
                    <span class="employee__block__label">Last Name</span>
                    <p>{{$employee->last_name}}</p>
                </div>
                <div class="salary">
                    <span class="employee__block__label">Salary</span>
                    <p>{{$employee->salary}} $</p>
                </div>
                <div  class="salary">
                    <span class="employee__block__label">Position</span>
                    <p>{{$employee->position->name}} </p>
                </div>
                <div class="date">
                    <span class="employee__block__label">Employment date</span>
                    <p>{{$employee->data_reception}}</p>
                </div>
                <div class="boss">
                    <span class="employee__block__label">Boss</span>
                    <p>{{$employee->boss->isNotEmpty() ? $employee->boss->first()->first_name . ' ' . $employee->boss->first()->last_name : ''}}</p>
                </div>
                <div class="remove__item">X</div>
            </div>
        @endforeach
    @endif
