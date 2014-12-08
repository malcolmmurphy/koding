
var Vitals = {
    temperature: {
        data: {},
        unit: 'f',
        init: function(data) {
            this.data = data;
            this.data.diff = data.estTemp - data.latestTemp;
        },
        increment: function() {
            var pct = ((new Date().getTime() / 1000) - this.data.latestTime) / this.data.span;
            var currentTemp = this.data.latestTemp + (this.data.diff * pct);
            if (this.unit == 'f') {
                currentTemp *= 9;
                currentTemp /= 5;
                currentTemp += 32;
            }
            $('#temperature').html(currentTemp.toString().substr(0, 14));
        },
        changeUnit: function(unit) {
            this.unit = unit;
        }
    },
    seaLevel: {
        data: {},
        unit: 'mm',
        init: function(mmPerYear) {
            this.data.mmPerYear = mmPerYear;
            $('#sea_level_mm_per_year').html(mmPerYear + ' mm').toggle(this.unit == 'mm');
            $('#sea_level_inches_per_year').html((mmPerYear * 0.0393701).toFixed(2) + ' inches').toggle(this.unit == 'inches');
            var kmCubedPerYear = 360000000 * (mmPerYear / 1000000);
            var mCubedPerYear = kmCubedPerYear * 1000000000;
            var mCubedPerSecond = mCubedPerYear / (60*60*24*365);
            this.ossPerSecond = mCubedPerSecond / 2500;
            this.ossGroups = 0;
        },
        increment: function(elapsedSeconds) {
            var groupSize = 100;

            var pct = elapsedSeconds / 60 / 60 / 24 / 365;
            var rise = this.data.mmPerYear * pct;
            var oss = Math.ceil(this.ossPerSecond * elapsedSeconds);
            var ossGroups = Math.floor(oss / groupSize);
            if (ossGroups > this.ossGroups) {
                var newGroup = true;
                this.ossGroups = ossGroups;
            }
            var ossRem = oss % groupSize;
            var ossUnfilled = groupSize - ossRem;
            var ossDots = '';
            
            if (newGroup) {
                var $c = $('#oss').clone();
                $c.attr('id', null);
                $('#oss').parent()
                    .append($c.css({color: '#09f'}))
                    .scrollTop($('#oss').height());
                $c.slideUp({
                    duration: 3000,
                    easing: 'easeInOutSine',
                    complete: function() {
                        $(this).remove();
                    }
                });
            }

            for(var i = 1; i <= groupSize; i++) {
                ossDots += '■ ';
                if (i == ossUnfilled || !ossUnfilled) {
                    ossDots += '<span style="color: #09f;">';
                }
                if (i % 10 == 0) ossDots += '<br>';
            }
            ossDots += '</span>';
            $('#oss').html(ossDots);
            $('#oss_count').html(oss);
            
            if (this.unit == 'inches') {
                rise *= 0.0393701;
                rise = rise.toFixed(10);
            } else {
                rise = rise.toFixed(8);
            }
            $('#sea_level').html(rise);
        },
        changeUnit: function(unit) {
            this.unit = unit;
            $('#sea_level_mm_per_year').toggle(unit == 'mm');
            $('#sea_level_inches_per_year').toggle(unit == 'inches');
        }
    },
    co2: {
        data: {},
        init: function(data) {
            this.data = data;
        },
        increment: function(elapsedSeconds) {
            var currentPpm = this.data.currentPpm + (elapsedSeconds * this.data.ppmPerSecond);
            $('#co2_ppm').html(currentPpm.toFixed(9));
        }
    }
};

var started = new Date().getTime();
var incrementVitals = function() {
    for (var vital in Vitals) {
        var elapsedSeconds = (new Date().getTime() - started) / 1000;
        Vitals[vital].increment(elapsedSeconds);
    }
}

var bc;

var StarField = {
    canvas: false,
    ctx: false,
    interval: false,
    random: function(min, max) {
        return Math.floor(Math.random() * (max - min - 1)) + min;
    },
    stop: false,
    iteration: function() {
        bc = 0;
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        for(var i = 0; i < this.stars.length; i++) {
            if (false === this.stars[i].z) continue;

            this.stars[i].z -= Math.max(3, (this.active / this.stars.length) * 9);
     
            if (this.stars[i].z < 1) {
                if (this.stop) {
                    this.stars[i].z = false;
                    this.active --;
                    continue;
                }
                this.stars[i].x = this.random(-this.width * 2, this.width * 2);
                this.stars[i].y = this.random(-this.height * 2, this.height * 2);
                this.stars[i].z = this.random(1, 500);
            }
     
            var k  = 25 / this.stars[i].z;
            var px = this.stars[i].x * k + (this.width / 2);
            var py = this.stars[i].y * k + (this.height / 2);

            if(px < 0 || px > this.width || py < 0 || py > this.height) continue;

            var size = parseInt((1 - (this.stars[i].z / 500)) * 4);
            var shade = parseInt((1 - (this.stars[i].z / 500)) * 255);
            this.ctx.fillStyle = "rgb(" + shade + "," + shade + "," + shade + ")";
            this.ctx.fillRect(px, py, size, size);
        }
        if (this.active / this.stars.length < 0.1) {
            $('#earth').show().animate({
                width: $(window).height(),
                marginTop: -$(window).height() / 2,
                height: $(window).height(),
                marginLeft: -$(window).height() / 2
            }, {
                duration: 2000,
                easing: 'easeInOutCubic',
                complete: function() {
                    $(this).stop(true, true);
                    incrementStarted = new Date().getTime();
                    setInterval(incrementVitals, 100);
                    $('#vitals_container').fadeIn(1000);
                }
            });
        } 
        if (!this.active) {
            clearInterval(this.interval);
            $('#star_field').remove();
        }
    },
    init: function() {
        this.canvas = $('#star_field')[0];
        if (!this.canvas || !this.canvas.getContext) return false;
        this.width = $(window).innerWidth();
        this.height = $(window).height();
        this.canvas.width = this.width;
        this.canvas.height = this.height;
        this.ctx = this.canvas.getContext('2d');
        this.stars = [];
        this.active = 1000;
        for(var i = 1; i <= this.active; i ++) {
            this.stars.push({z:1});
        }
        var that = this;
        this.interval = setInterval(function() {
            that.iteration();
        }, 30);
    }
};

var drawChart = function() {

    var data = new google.visualization.DataTable();
    data.addColumn('number', 'year');
    data.addColumn('number', 'temp');
    
    for(var year in vitalsData.temperature.allTemps) {
        data.addRow([parseInt(year), parseFloat(vitalsData.temperature.allTemps[year])]);
    }
    
    var options = {
        width: 768,
        height: 400,
        hAxis: {
            title: 'Year'
        },
        vAxis: {
            title: '°C'
        },
        backgroundColor: {
            'fill': '#000',
            'opacity': 100
        },
        colors: ['#f00'],
        legend: {position: 'none'}
    };
    
    var chart = new google.visualization.LineChart(document.getElementById('temp_chart'));
    chart.draw(data, options);
}


jQuery(function($) {
    
    // load vitals data (vitalsData JSON set in view)
    for(var vital in vitalsData) {
        Vitals[vital].init(vitalsData[vital]);
    }
        
    $('#select').mousedown(function() {
        $(this).removeClass('active').addClass('on');
        $('.option.hidden').show();
    });
    
    $('select').selectOrDie({
        onChange: function() {
            Vitals[$(this).attr('data-vital')][$(this).attr('data-callback')]($(this).val());
        }
    });
    
    $('button').button();
    $('button, .a').click(function() {
        var section = $(this).attr('data-show');
        $('#vitals h2, .desc').hide();
        $(section).fadeIn();
    });
    
    $('#go').click(function() {
        $('#screen').fadeOut(function() {
            $(this).remove();
        });
        $('#universe, #credits').fadeIn();
        $('#star_field').hide().fadeIn(2000);
        StarField.init();
        $('#universe').animate({backgroundSize: '400%', opacity: 0.3}, 1500, 'easeInQuad', function() {
            StarField.stop = true;
            $(this).animate({backgroundSize: '800%', opacity: 0}, 1500, 'easeOutQuad', function() {
                $(this).remove();
            });
        });
    });
    
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawChart);

});