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
                        <option {{$currentDepartment === $department ? 'selected' : '' }} >{{$department->name}}</option>
                    @endforeach
                </select>
                <label for="sort">Сортировать</label>
                <select id="sort">
                    <option>Без сортировки</option>
                    <option>First Name</option>
                    <option>Last Name</option>
                    <option>Salary</option>
                    <option>Position</option>
                </select>
                <button class="btn addUser">Add User</button>
            </form>
        </aside>
        @if($employees->isNotEmpty())
        <div class="employees" current_page="{{ route('paginationEmployees') }}" last_page="{{$employees->lastPage()}}">
            <form action="#" class="search__form">
                <p>Search employee:</p>
                <select name="search-employee">
                    <option>First Name</option>
                    <option>Last Name</option>
                    <option>Salary</option>
                    <option>Employment date</option>
                    <option>boss</option>
                </select>
                <input type="text">
            </form>
            @include('employeesItem')
        </div>
        @endif
    </div>
@endsection