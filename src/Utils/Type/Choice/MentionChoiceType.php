<?php

namespace App\Utils\Type\Choice;


class MentionChoiceType
{

    const MENTION_PASSABLE = 'P';
    const MENTION_ASSEZ_BIEN = 'AB';
    const MENTION_BIEN = 'B';
    const MENTION_TRES_BIEN = 'TB';
    const MENTION_EXCELLENT = 'EXC';
    const MENTION_HONORABLE = 'HON';
    const MENTION_THONORABLE = 'THON';

    protected static $choices = [
        self::MENTION_PASSABLE       =>     'mention.passable',
        self::MENTION_ASSEZ_BIEN     =>     'mention.abien',
        self::MENTION_BIEN           =>     'mention.bien',
        self::MENTION_TRES_BIEN      =>     'mention.tbien',
        self::MENTION_EXCELLENT      =>     'mention.excellent',
        self::MENTION_HONORABLE      =>     'mention.honorable',
        self::MENTION_THONORABLE     =>     'mention.thonorable'
    ];

    public static function getChoices()
    {
        return array_flip(static::$choices);
    }
}