<?php

enum BatchStatus: string
{
    case AVAILABLE = 'AVAILABLE';
    case LOW = 'LOW';
    case EXPIRED = 'EXPIRED';
}
