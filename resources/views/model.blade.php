<div class="modal" style="display: flex">
    <div class="modal__close">X</div>
    @if(session('message'))
        <p>{{session('message')}}</p>
    @endif
</div>
