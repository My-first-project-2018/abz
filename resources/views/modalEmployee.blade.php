<div class="modal">
    <div class="modal__close">X</div>

    <form action="{{route('createEmployee')}}" method="post" enctype="multipart/form-data">
        <div class="user__info">
            <label> Имя
                <input type="text" name="first_name" placeholder="new user first name">
            </label>
            <label> Фамилия
                <input type="text" name="last_name" placeholder="new user last name">
            </label>
            <label> з-п
                <input type="text" name="salary" placeholder="new user salary">
            </label>
            <label> Дата приема на работу
                <input type="text" name="data_reception" placeholder="new user employment date">
            </label>
            <label> Должность
                <select name="position">
                    @foreach($positions as $position)
                        <option value="{{$position->hash}}">{{$position->name}}</option>
                    @endforeach
                </select>
            </label>
            <label> Начальник
                <input type="text" name="boss" placeholder="new user boss">
            </label>
            <button type="submit"  class="btn">Добавить</button>
        </div>
        <div class="file__input">
            <div class="file__upload">
                <label>
                    <input type="file" name="img">
                    <span>Выбрать фото</span>
                </label>
            </div>
            <img class="upload_image" src="" alt="">
            <div class="upload_image_container"></div>
        </div>
    </form>
</div>
