$(document).ready(() => {

//modal window
    let verstka = '<p class="modal_text"> Verstka, eptet\'</p>';

    function openModal (content) {
        let modal = '<div class="modal"><div class="modal__close">X</div></div>';
        $('body').prepend(modal);
        $('.modal').append(content);

        $('.modal__close').on('click', () => {
            $('.modal').remove();
        });
    }

//dich

    let departments = $('.departments__item'),
        departmentContent = $('.departments__content'),
        closeDepartment = $('.close_department'),
        loginWindow = $('.login_window'),
        isOpened = false,
        leftPos,
        topPos,
        index = 0;

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
            departmentContent.addClass('departments__content_active');
        },400);

    });

    closeDepartment.on('click', function () {
        departmentContent.removeClass('departments__content_active');
        let departmentItem = $(this).parent();
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


    let vipadashka = '<ul class="subordinate subordinate_second" data-hierarchy="3">\n' +
                    '    <li class="subordinate__item">\n' +
                    '        <p class="name">Petro Wkalik</p>\n' +
                    '        <p class="position">Rab</p>\n' +
                    '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
                    '    </li>\n' +
                    '    <li class="subordinate__item">\n' +
                    '        <p class="name">Petro Wkalik</p>\n' +
                    '        <p class="position">Rab</p>\n' +
                    '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
                    '    </li>\n' +
                    '    <li class="subordinate__item">\n' +
                    '        <p class="name">Petro Wkalik</p>\n' +
                    '        <p class="position">Rab</p>\n' +
                    '        <div class="show_subordinate"><img src="img/next.svg" alt=""></div>\n' +
                    '    </li>\n' +
                    '</ul>';

    departmentContent.on('click','.subordinate__item', function () {
        $('.subordinate__item').removeClass('subordinate__item_active');
        $(this).addClass('subordinate__item_active');
        let hierarchy = $(this).parent('.subordinate').attr('data-hierarchy');
        $('.subordinate').each((i, item) => {
            if($(item).attr('data-hierarchy') > hierarchy + 2) {
                $(item).remove();
            }
        });
        departmentContent.append(vipadashka);
    });

});