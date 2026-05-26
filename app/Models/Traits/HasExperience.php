<?php

namespace App\Models\Traits;

trait HasExperience
{
    public function addXp($amount)
    {
        $this->xp += $amount;
        
        $xpRemaining = $this->xp;
        $level = 1;
        
        // Level 1 to 15 (requires 2000 each)
        while ($xpRemaining >= 2000 && $level < 15) {
            $xpRemaining -= 2000;
            $level++;
        }
        
        // Level 16 to 30 (requires 1850 each)
        if ($level >= 15) {
            while ($xpRemaining >= 1850 && $level < 30) {
                $xpRemaining -= 1850;
                $level++;
            }
        }
        
        // Level 30+ (requires 2000 each)
        if ($level >= 30) {
            while ($xpRemaining >= 2000) {
                $xpRemaining -= 2000;
                $level++;
            }
        }
        
        $this->level = $level;
        $this->save();
        
        return $this->level;
    }

    public function xpForNextLevel()
    {
        if ($this->level < 15) return 2000;
        if ($this->level < 30) return 1850;
        return 2000;
    }
    
    public function currentLevelProgress()
    {
        $xpRemaining = $this->xp;
        $level = 1;
        
        while ($xpRemaining >= 2000 && $level < 15) {
            $xpRemaining -= 2000;
            $level++;
        }
        if ($level >= 15) {
            while ($xpRemaining >= 1850 && $level < 30) {
                $xpRemaining -= 1850;
                $level++;
            }
        }
        if ($level >= 30) {
            while ($xpRemaining >= 2000) {
                $xpRemaining -= 2000;
                $level++;
            }
        }
        
        $required = $this->xpForNextLevel();
        return min(100, round(($xpRemaining / $required) * 100));
    }
}
