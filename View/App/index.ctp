<div id="screen">

    <div id="intro">
        <h1>PlanetVitals<span>.localhost</span></h1>
        <h2>real-time planetary vital sign monitoring</h2>
    
        <div style="position: relative; margin-top: 3em; width: 100%;">
    
            <div class="button">
                <div id="select" class="option active">Select Your Home Planet ▾</div>
                <div id="go" class="option active hidden">Earth</div>
                <div class="option disabled hidden">(no other options available at this time)</div>
            </div>
        
        </div>
    </div>
    
</div>

<div id="universe"></div>

<canvas id='star_field' width='1000' height='1000'></canvas>

<div id="earth"></div>

<div id="vitals_container">
    <div id="vitals">
    
        <h2>
            <span class="vital-label">Sea Level</span><span style="color: red;">▲</span> <span id="sea_level" style="font-family: Monaco;">loading</span>
            <select data-vital="seaLevel" data-callback="changeUnit">
                <option value="mm" selected> mm</option>
                <option value="inches"> inches</option>
            </select>
            <div><button data-show="#sea_level_desc">More Information</button></div>
        </h2>

    
        <div class="desc" id="sea_level_desc">
            <h3>
                Your planet's sea levels are rising at an exponential rate,
                currently about <span id="sea_level_mm_per_year"></span><span id="sea_level_inches_per_year"></span> per year.
            </h3>
            
            <p>
                <strong>Current Symptoms</strong>
                Coastal regions are flooding more frequently.
            </p>
            
            <p>
                <strong>If Left Untreated</strong>
                Hundreds of millions of people may be displaced in the next century.
            </p>
    
            <p>
                <strong>Visualization</strong>
                Each blue dot below represents the amount of water from an Olympic-size swimming pool
                filling up your planet's oceans in real-time.
            </p>
    
            <div>
                <div style="overflow-y: hidden; overflow-x: visible; white-space: nowrap; height: 10em; width: 100%; text-align: center; font-size: 2em; line-height: 1em; letter-spacing: 0.1em; color: #446;">
                    <div id="oss"></div>
                </div>
                <div style="font-size: 75%; width: 100%; text-align: center;">
                    <span id="oss_count">0</span> Olympic-size pools since your visit.<br/>
                </div>
            </div>
            
            <p>
                <strong>Cause</strong>
                <span class="a" data-show="#temp_desc">Increasing temperatures</span> are causing ocean water to expand, in addition
                to melting polar ice caps and glaciers.
            </p>

            <div><button data-show="#vitals h2">Return To Vitals</button></div>
        </div>
            
        <h2>
            <span class="vital-label">Temperature</span>
            <span style="color: red;">▲</span>
            <span id="temperature" style="font-family: Monaco;">loading</span>
            <select data-vital="temperature" data-callback="changeUnit">
                <option value="f" selected>°F</option>
                <option value="c">°C</option>
            </select>
            <div><button data-show="#temp_desc">More Information</button></div>
        </h2>
        
        <div class="desc" id="temp_desc">
            <h3>
                Your planet's temperature is increasing at an exponential rate.
            </h3>
            
            <p>
                <strong>Current Symptoms</strong>
                Extended droughts severe leading to substantial crop loss and increased wildfires.
            </p>
            
            <p>
                <strong>If Left Untreated</strong>
                Your planet may suffer from irreversible damage or even premature death.
            </p>

            <p>
                <strong>Visualization</strong>
                <div id="temp_chart" style="width: 100%"></div>
            </p>

            <p>
                <strong>Cause</strong>
                <span class="a" data-show="#co2_desc">Increasing CO<sub>2</sub> emissions</span> are trapping solar energy in the atmosphere.
            </p>
                
            <div><button data-show="#vitals h2">Return To Vitals</button></div>
        </div>
    
        <h2>
            <span class="vital-label">Atmospheric CO<sub>2</sub></span><span style="color: red;">▲</span> <span id="co2_ppm" style="font-family: Monaco;">loading</span> ppm
            <div><button data-show="#co2_desc">More Information</button></div>
        </h2>
    
        <div class="desc" id="co2_desc">
            <h3>
                Your planet's atmosphere exceeds the upper safety limit of 350 parts per million.            
            </h3>
            <p>
                High levels of CO<sub>2</sub> and other greenhouse gasses are trapping
                solar heat in your planet's thin and fragile atmosphere, which is 
                causing temperatures and sea levels to rise at an exponential rate.
            </p>
            <p>
                <strong>Treatment</strong>
                About 70% of greenhouse gas emissions come from transportation and electricity production.
                There are <a href="https://www.google.com/#q=how+to+reduce+my+carbon+footprint" target="_blank">many things you can do</a>
                to help reduce your own carbon footprint. In addition, pressure your local legislators to make healthy
                decisions for your planet, and do not elect or re-elect any that will put it in danger.
            </p>
            <div><button data-show="#vitals h2">Return To Vitals</button></div>
        </div>
    
    </div>
</div>
    
<div id="credits">Universe and Earth images from NASA</div>
    
<div class="ui-helper-clearfix"></div>

<script type="text/javascript">
    var vitalsData = <?php echo json_encode($vitalsData); ?>;
</script>

