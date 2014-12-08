<script type="text/javascript">
    var latestTemp = <?php echo $latestTemp; ?>;
    var latestTime = <?php echo $latestTime; ?>;
    var estTemp = <?php echo $estTemp; ?>;
    var span = <?php echo $span; ?>;
    var diff = estTemp - latestTemp;

    function inc() {
        var pct = ((new Date().getTime() / 1000) - latestTime) / span;
        var currentTemp = latestTemp + (diff * pct);
        $('#ct').html(currentTemp.toString().substr(0, 14));
    }

    jQuery(function($) {
        setInterval(inc, 100);
    });
</script>

<h2>
    Average Temperature: <span id="ct" style="font-family: Monaco;">loading</span>°C
</h2>