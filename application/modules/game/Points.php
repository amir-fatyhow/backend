<?php

class Points {
    private $ax;
    private $ay;
    private $az;

    private $bx;
    private $by;
    private $bz;

    private $cx;
    private $cy;
    private $cz;

    private $connection;

    function __construct($connection, $ax, $ay, $az, $bx, $by, $bz, $cx, $cy, $cz) {
        $this->connection = $connection;

        $this->ax = $ax;
        $this->ay = $ay;
        $this->az = $az;

        $this->bx = $bx;
        $this->by = $by;
        $this->bz = $bz;

        $this->cx = $cx;
        $this->cy = $cy;
        $this->cz = $cz;
    }

    private function side($x1, $y1, $z1, $x2, $y2, $z2)
    {
        $x = abs($x1 - $x2);
        $y = abs($y1 - $y2);
        $z = abs($z1 - $z2);
        return round(sqrt($x * $x + $y * $y + $z * $z), 2);
    }

    private function corners($a, $b, $c)
    {
        $cosA = ($c * $c + $b * $b - $a * $a) / (2 * $c * $b);
        $cosB = ($c * $c + $a * $a - $b * $b) / (2 * $c * $a);
        $cosC = ($a * $a + $b * $b - $c * $c) / (2 * $a * $b);
        return [round(acos($cosA) * 57.3), round(acos($cosB) * 57.3), round(acos($cosC) * 57.3)];
    }

    private function validate($a, $b, $c)
    {
        if ($a > $b + $c || $b > $a + $c || $c > $b + $a)
        {
            return false;
        }
        return true;
    }

    private function square($a, $b, $c)
    {
        if (!$this->validate($a, $b, $c))
        {
            return false;
        }
        $p = ($a + $b + $c) / 2;
        return round(sqrt($p * ($p - $a) * ($p - $b) * ($p - $c)), 2);
    }

    private function getArray($result) {
        $answer = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $answer[] = $row;
            }
        }
        return $answer;
    }

    private function sendPoints() {
        $sideOne = $this->side($this->ax, $this->ay, $this->az, $this->bx, $this->by, $this->bz);
        $sideTwo = $this->side($this->cx, $this->cy, $this->cz, $this->bx, $this->by, $this->bz);
        $sideThree = $this->side($this->ax, $this->ay, $this->az, $this->cx, $this->cy, $this->cz);

        $square = $this->square($sideOne, $sideTwo, $sideThree);

        /**
        Если треугольник существует, то отправляем данные в таблицу
         * */
        if ($square) {
            list($corner1, $corner2, $corner3) = $this->corners($sideOne, $sideTwo, $sideThree);
            $insert = "INSERT INTO points(side_a, side_b, side_c, square, angle1, angle2, angle3)
            VALUES(". $sideOne . ",". $sideTwo . ",". $sideThree . ","
                . $square . ",". $corner1 . ",". $corner2 . ","
                . $corner3 . ")";
            $this->connection->query($insert);
            return true;
        }
        return false;
    }

    public function getSides() {
        $answer = $this->sendPoints();
        if ($answer) {
            $select = "SELECT side_a, side_b, side_c FROM points";
            $result = $this->connection->query($select);
            return $this->getArray($result);
        } else {
            return array (
                'error' => 'triangle with entered points does not exist'
            );
        }
    }

    public function getAngles() {
        $answer = $this->sendPoints();
        if ($answer) {
            $select = "SELECT angle1, angle2, angle3 FROM points";
            $result = $this->connection->query($select);
            return $this->getArray($result);
        } else {
            return array (
                'error' => 'triangle with entered points does not exist'
            );
        }
    }

    public function getSquare() {
        $answer = $this->sendPoints();
        if ($answer) {
            $select = "SELECT square FROM points";
            $result = $this->connection->query($select);
            return $this->getArray($result);
        } else {
            return array (
                'error' => 'triangle with entered points does not exist'
            );
        }

    }
}