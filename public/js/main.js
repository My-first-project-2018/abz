$(document).ready(() => {

//modal window

    let modal = $('.modal');

    $('.addUser').on('click', (e) => {
        e.preventDefault();
        modal.css({'display':'flex'});
    });


    $('.modal__close').on('click', () => {
        modal.css({'display':'none'});
    });


//dich

    let departments = $('.departments__item'),
        departmentContent = $('.departments__content'),
        closeDepartment = $('.close_department'),
        loginWindow = $('.login_window'),
        timer,
        draggable = false,
        dragItem = null,
        shiftY,
        shiftX,
        isOpened = false,
        leftPos,
        topPos;

    $('body').click((e) => {
        if(!$(e.target).closest('.login').length && !$(e.target).closest('.login_window').length) loginWindow.slideUp();
    });

    $('.login').click(() => {
        loginWindow.slideToggle();
    });

    departments.on('click',function () {
        if(isOpened) {
            return;
        }

        leftPos = $(this).offset().left;
        topPos = $(this).offset().top;
        $(this).css({'left':`${leftPos - 20}px`});
        $(this).css({'top':`${topPos - 20}px`});
        setTimeout(() => {
            $(this).addClass('departments__item_active');
            departments.each((i, item) => {
                if (item !== this) {
                    $(item).addClass('departments__item_disabled');
                }
            });
            isOpened = true;
        },1);

        setTimeout(() => {
            $(this).find('.close_department').addClass('close_department_active');
            $(this).find('.departments__content').addClass('departments__content_active');
            setTimeout(() => {
                $(this).find('.departments__content').addClass('visible');
            },10);
        },400);

    });

    closeDepartment.on('click', function () {
        let departmentItem = $(this).parent();
        departmentItem.find('.departments__content').removeClass('departments__content_active visible');
        $(this).removeClass('close_department_active');
        departmentItem
            .removeClass('departments__item_active')
            .css({
                'position':'absolute',
                'left':`${leftPos}px !important`,
                'top':`${topPos}px !important`
            });
        setTimeout(() => {
            departmentItem.css({
                'position':'static',
                'left':'auto',
                'top':'auto'
            });
            departments.each((i, item) => {
                if (item !== this) {
                    $(item).removeClass('departments__item_disabled');
                }
            });
            isOpened = false;
        },400);
    });


    departmentContent.on('click','.subordinate__item', function () {
        let hierarchy = $(this).parent('.subordinate').attr('data-hierarchy');

        if(hierarchy == 5) return;

        if(!draggable) {
            $(this).parent().find('.subordinate__item').removeClass('subordinate__item_active');
            $(this).addClass('subordinate__item_active');

            $('.subordinate').each((i, item) => {
                if($(item).attr('data-hierarchy') > hierarchy + 2) {
                    $(item).remove();
                }
            });
            let verstka = getWorkersList(+hierarchy + 1);

            departmentContent.append(verstka);
        }
    });


    //drag'n'drop 

    departmentContent.on('mousedown', '.subordinate__item', function (e) {
        dragItem = $(this);
        if (dragItem.hasClass('subordinate__item_active')) return;
        shiftY = e.clientY - dragItem.offset().top;
        shiftX = e.clientX - dragItem.offset().left;
        timer = setTimeout(() => {
            draggable = true;
        },200);
    });
    departments.on('mouseup', (e) => {
        clearTimeout(timer);
        if(draggable) {
            setTimeout(() => {
                draggable = false;
            },10);
            let item = $(e.target).closest('.subordinate__item');
            item.css({'display':'none'});
            let director = $(document.elementFromPoint(e.clientX, e.clientY)).closest('.subordinate__item');
            item.css({'display':'block'});
            console.log(director);
        }

        return false;
        
    });



    $(document).on('mousemove', (e) => {
       if(draggable)  moveItem(e);
    });

    //upload file

    $("input[type='file']").on('change', function () {


        let file = this.files[0];

        if(file.type.match(/image/)) {
            $('.upload_image_container').html('');

            $('.upload_image').addClass('upload_image__active');

            let reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = (function () {
                setTimeout(() => {
                    $('.upload_image').attr('src', reader.result);
                },100)

            })(file);

        } else {
            console.log('not image');
            $('.upload_image').removeClass('upload_image__active').attr('src', '');
            $('.upload_image_container').html('THIS IS NOT IMAGE');
        }













        //hierarchy



    });


    function moveItem (e) {
        dragItem.css({
            'position':'absolute',
            'left':`${e.clientX - shiftX}px`,
            'top':`${e.clientY - shiftY}px`,
            'z-index':'10'
        })
    }


    function getWorkersList (hierarchy) {
        return `<ul class="subordinate" data-hierarchy="${hierarchy}">\n` +
            '    <li class="subordinate__item">\n' +
            '        <p class="name">Petro Shkalik</p>\n' +
            '        <p class="position">Rab</p>\n' +
            '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
            '    </li>\n' +
            '    <li class="subordinate__item">\n' +
            '        <p class="name">Petro Shkalik</p>\n' +
            '        <p class="position">Rab</p>\n' +
            '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
            '    </li>\n' +
            '    <li class="subordinate__item">\n' +
            '        <p class="name">Petro Shkalik</p>\n' +
            '        <p class="position">Rab</p>\n' +
            '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
            '    </li>\n' +
            '    <li class="subordinate__item">\n' +
            '        <p class="name">Petro Shkalik</p>\n' +
            '        <p class="position">Rab</p>\n' +
            '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
            '    </li>\n' +
            '    <li class="subordinate__item">\n' +
            '        <p class="name">Petro Shkalik</p>\n' +
            '        <p class="position">Rab</p>\n' +
            '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
            '    </li>\n' +
            '    <li class="subordinate__item">\n' +
            '        <p class="name">Petro Shkalik</p>\n' +
            '        <p class="position">Rab</p>\n' +
            '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
            '    </li>\n' +
            '</ul>';
    }
    
    

});


