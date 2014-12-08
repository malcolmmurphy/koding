<?php
    
class Co2 extends AppModel
{
    
    public function load()
    {
        // Nov 2014 amt: 397.13
        // (http://co2now.org/current-co2/co2-now/annual-co2.html)
        $dataTime = mktime(0,0,0,11,01,2014);
        $elapsed = time() - $dataTime;
        $ppm = 397.13;

        // 2004-2013 rate of increase per year: 2.07ppm per year
        // (http://co2now.org/Current-CO2/CO2-Trend/acceleration-of-atmospheric-co2.html)
        $ppmPerSecond = 2.07 / 365 / 24 / 60 / 60;
        $currentPpm = $ppm + ($elapsed * $ppmPerSecond);
    
        // emmisions estimate:
        // (http://co2now.org/Current-CO2/CO2-Now/global-carbon-emissions.html)
        $tonnesPerYear = 36900000000;
    
        return compact('ppmPerSecond', 'currentPpm', 'tonnesPerYear');
    }
}
