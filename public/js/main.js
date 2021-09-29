
// Minimize side navigation.
function close_nav() {
    $('#navigation').addClass('minimized-nav-w');
    $('#page-content').addClass('minimized-nav-screen');
    $('.nav-text').addClass('hidden');
    $('.nav-icons').removeClass('hidden');
    $('#nav-control').css('transform', 'rotate(180deg)');
    setTimeout(() => {
        $('#nav-control').addClass('absolute');
        $('#nav-control-cover').removeClass('hidden');
    }, 250);
}

// Maximize side navigation.
function open_nav() {
    $('#navigation').removeClass('minimized-nav-w');
    $('#page-content').removeClass('minimized-nav-screen');
    $('#nav-control').removeClass('absolute');
    $('.nav-text').removeClass('hidden');
    $('#nav-control-cover').addClass('hidden');
    $('.nav-icons').addClass('hidden');
    $('#nav-control').css('transform', 'rotate(0)');
}

// Event listener for side navigation.
let nav_open = true;

$('#nav-control').on('click', function() {
    if(nav_open) {
        close_nav();
        nav_open = false;
    } else if (!nav_open) {
        open_nav();
        nav_open = true;
    }
});

// Side navigation option drop downs.
let nav_dropdown_triggers = Array.prototype.slice.call($('.nav-dropdown-trigger'));
let nav_dropdown = Array.prototype.slice.call($('.nav-dropdown'));
let nav_break = Array.prototype.slice.call($('.nav-break'));
let open_nav_dropdown = [
    true,
    true
];

nav_dropdown_triggers.forEach((nav_dropdown_trigger, index) => {
    nav_dropdown_trigger.addEventListener('click', function(e) {
        e.preventDefault()
        if(!open_nav_dropdown[index]) {
            $(nav_dropdown[index]).slideUp();
            $(nav_break[index]).removeClass('w-full');
            open_nav_dropdown[index] = true;
        } else if (open_nav_dropdown[index]) {
            $(nav_dropdown[index]).slideDown();
            $(nav_break[index]).addClass('w-full');
            open_nav_dropdown[index] = false;
        }
    })
});
