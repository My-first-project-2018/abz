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
        @include('employeesItem')
    </div>
@endsection