<?php
    
class SandboxShell extends AppShell
{
    
    public $uses = array('AnnualMeanTemp');
    
    public function main()
    {

        $set = $this->AnnualMeanTemp->find('list', array(
            'fields' => array('year', 'celsius'),
        ));

        $avgDiffs = array();
        
        for($setSize = 2; $setSize <= 50; $setSize ++) {

            $avgDiffs[$setSize] = array();

            for($ahead = 50; $ahead <= 50; $ahead ++) {

                echo $setSize . ':' . $ahead . '   ' . "\r";

                $diffs = array();
                for($y1 = 1880 + $setSize; $y1 <= 2013 - $ahead; $y1 ++) {
                    $next = $this->AnnualMeanTemp->predict($y1, $setSize, $ahead);
                    $actual = $set[$y1 + $ahead];
                    $diffs[] = $next - $actual;
                }
                $avgDiffs[$setSize][$ahead] = array_sum($diffs) / count($diffs);

            }

        }
        
        echo "\n\n";
        
        $n = array();
        foreach($avgDiffs as $setSize => $group) {
            $n[$setSize] = array_sum($group) / count($group);
        }

        print_r($n);
        $this->out();
    }

}