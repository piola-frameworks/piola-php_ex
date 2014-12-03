<?php

namespace piola\db
{
    class EIsolationLevel extends \SplEnum
    {
        const _default = self::Unspecified;

        const Unspecified = 0;
        const Chaos = 1;
        const ReadCommitted = 2;
        const ReadUncommitted = 3;
        const RepeatableRead = 4;
        const Serializable = 5;
        const Snapshot = 6;
    }
}

?>