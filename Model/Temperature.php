<?php
    
class Temperature extends AppModel
{
    
    function update()
    {
        // get annual mean tempuratures since 1801 through previous year
        // using file from http://data.giss.nasa.gov/gistemp/tabledata_v3/GLB.Ts+dSST.txt
        $lines = explode("\n", file_get_contents(APP . 'Lib/data/GLB.Ts+dSST.txt'));
        foreach($lines as $line) {
            if (is_numeric(substr($line, 0, 4))) {
                $lineData = explode(' ', preg_replace('/\s+/', ' ', $line));
                if (($year = array_shift($lineData)) == 1880) continue;
                $anomaly = $lineData[12];
                if (!is_numeric($anomaly)) break;
                $annualMeans[$year] = ($anomaly / 100) + 14; // add Best estimate for absolute global mean for 1951-1980
            }
        }

        // reset table and add data
        $this->query('TRUNCATE temperatures');
        foreach($annualMeans as $year => $annualMean) {
            $this->create();
            $this->save(array(
                'year' => $year,
                'celsius' => $annualMean,
            ));
        }
        return true;
    }
    
    public function latest()
    {
        return $this->field('year', array(true), array('year DESC'));
    }
    
    function trend($ending = null, $n = 10)
    {
        $set = $this->find('list', array(
            'fields' => array('year', 'celsius'),
            'conditions' => array('year <=' => $ending ? $ending : 10000),
            'order' => array('id DESC'),
            'limit' => $n,
        ));
        ksort($set);
        $xSum = array_sum(array_keys($set));
        $ySum = array_sum($set);
        
        $a = 0;
        $c = 0;
        foreach($set as $x => $y) {
            $a += ($x * $y);
            $c += pow($x, 2);
        }
        $a *= $n;
        $b = $xSum * $ySum;
        $c *= $n;
        $d = pow($xSum, 2);
        $slope = ($a - $b) / ($c - $d);

        $e = $ySum;
        $f = $slope * $xSum;
        $yInt = ($e - $f) / $n;
        return array('m' => $slope, 'b' => $yInt);
    }
    
    function predict($latest = null, $yearsAhead = 1, $setSize = 10)
    {
        if (!$latest) $latest = $this->latest();
        $trend = $this->trend($latest, $setSize);
        return $trend['m'] * ($latest + $yearsAhead) + $trend['b'];
    }
    
    public function load()
    {
        $latest = $this->latest();
        $current = date('Y');
        $years = $current - $latest;
        $estTemp = $this->predict($latest, $years, 15);
        
        $latestTemp = (float) $this->field('celsius', array('year' => $latest));
        
        $latestTime = mktime(0, 0, 0, 1, 1, $latest + 1);
        $span = mktime(0, 0, 0, 1, 1, $current + 1) - $latestTime;
        
        $allTemps = $this->find('list', array(
            'fields' => array('year', 'celsius'),
        ));
        
        return compact('latestTemp', 'latestTime', 'estTemp', 'span', 'allTemps');
    }

}