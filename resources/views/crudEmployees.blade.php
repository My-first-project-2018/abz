@extends('layouts.site')

@section('header')
    @include('header')
@endsection

@section('content')
    <div class="crud__content">
        <aside class="crud__aside">
            <form class="crud__form" action="#">
                <label for="department">Отдел</label>
                <select id="department">
                    @foreach($departments as $department)
                        <option {{$currentDepartment === $department ? 'selected' : '' }} value="{{route('employeesDepartment', ['department' => $department->slug])}}" > {{$department->name}} </option>
                    @endforeach
                </select>

                <label for="sort">Сортировать</label>
                <select id="sort">
                    @if(isset($fields) && !empty($fields))
                        @foreach($fields as $key=>$field)
                            <option value="{{$key}}">{{$field}}</option>
                        @endforeach
                    @endif
                </select>
                <div class="order" data-url="{{route('orderByEmployees')}}">
                    <label>po ubivaniu
                        <input name="orderBy" type="radio" value="desk" checked>
                    </label>
                    <br>
                    <label>po vozrostaniyu
                        <input name="orderBy" type="radio" value="asc">
                    </label>
                </div>
                <button class="btn addUser">Add User</button>
            </form>
        </aside>
        @if($employees->isNotEmpty())
        <div class="employees" current_page="{{ route('paginationEmployees') }}" last_page="{{$employees->lastPage()}}">
            <form action="#" class="search__form">
                <p>Search employee:</p>
                <select name="search-employee">
                    @if(isset($fields) && !empty($fields))
                        @foreach($fields as $key=>$field)
                            <option value="{{$key}}">{{$field}}</option>
                        @endforeach
                    @endif
                </select>
                <input type="text">
            </form>
            @include('employeesItem')
        </div>
        @endif
    </div>
@endsection