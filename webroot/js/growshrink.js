function scaleBg(amt, grow)
{
    console.log(grow, amt);

    amt += grow ? 1 : -1;

    if (grow && amt > 20) grow = false;
    else if (!grow && amt < 0) grow = true;

    $('#bg').css({width: 100 + amt + '%', left: amt / -2 + '%'});

    setTimeout(function() {
        scaleBg(amt, grow);
    }, 1000);
}

jQuery(function($) {
    scaleBg(0, true);
})