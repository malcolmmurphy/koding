<?php
    
class SeaLevel extends AppModel
{
    
    public $useTable = 'sea_level';
    
    function update()
    {   
        
        // this isn't an accurate method, manually entered 3.048mm
        // http://oceanservice.noaa.gov/facts/sealevel.html
        
        throw new NotImplementedException('This data must be manually entered');
        
        // get annual mean seal level rise
        if (!($file = @file_get_contents('http://tidesandcurrents.noaa.gov/sltrends/downloadGlobalStationsLinearSeaLevelTrendsCSV.htm'))) {
            throw new InternalErrorException('Unable to retrieve data file');
        }
        if (!@file_put_contents(APP . 'Model/data/' . $this->name . '.txt', $file)) {
            throw new InternalErrorException('Unable to write data file');
        }
        $lines = explode("\n", $file);
        $trends = array();
        foreach($lines as $k => $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $lineData = str_getcsv($line);
            if (!$k) {
                // header row
                if (trim($lineData[6]) != 'MSL Trends (mm/yr)') {
                    throw new InternalErrorException('Unexpected format');
                }
                continue;
            }
            $trends[$lineData[0]] = $lineData[6];
        }
        $avg = array_sum($trends) / count($trends);
        $x = $this->save(array(
            'id' => 1,
            'mm_per_year' => $avg,
        ));
        return (bool) $this->getAffectedRows();
    }
    
    public function load()
    {
        return (float) $this->field('mm_per_year', array(true), array('created DESC'));
    }
    
}
