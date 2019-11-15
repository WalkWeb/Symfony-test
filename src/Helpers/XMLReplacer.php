<?php

class XMLReplacer
{
    private const ACTION_OPEN_TAG = 'OPEN_TAG';
    private const ACTION_CONTENT = 'CONTENT';
    private const ACTION_INJECTION_CLOSE_TAG = 'INJECTION_CLOSE_TAG';
    private const ACTION_CLOSED_TAG = 'CLOSED_TAG';

    /**
     * @var int - счетчик символов в XML
     */
    private $i = 0;
    
    /**
     * @var string - исходных XML
     */
    private $sourceXML;

    /**
     * @var string - исправленный XML
     */
    private $replaceXML;

    /**
     * @var array - Исходный XML конвертированный в массив, где каждая ячейка - один символ XML документа
     */
    private $array = [];

    /**
     * @var int - количество символов в XML
     */
    private $length;

    /**
     * @var array - Массив с открывающимися и закрывающимися тегами
     */
    private $tags = [];

    /**
     * @var int - Общий счетчик тегов
     */
    private $tagNumber = 1;

    /**
     * @var bool - Начался тег
     */
    private $startTag = false;

    /**
     * Номер символа в текущем теге. Этот счетчик нужен для того, чтобы мы понимали, что если открылся тег, и следующий
     * символ "/" - значит это закрывающий тег. А если "/" встретился третьим или последующим символов - то это мусорный
     * символ и его нужно удалить
     *
     * @var int
     */
    private $tagSymbolNumber = 1;

    /**
     * @var array - Массив с содержимым тегов
     */
    private $contents = [];

    /**
     * @var int  - Номер символа в текущем содержимом тегов т.е. <tag>содержимое</tag>
     */
    private $contentsNumber = 1;

    /**
     * @var bool - Началось содержимое контента
     */
    private $startContent = false;

    /**
     * @var array - Массив открывающих тегов
     */
    private $openTags = [];

    /**
     * @var int - Счетчик открывающихся тегов
     */
    private $openTagsNumber = 1;

    /**
     * @var bool - Начался открывающийся тег
     */
    private $startOpenTag = false;

    /**
     * @var array - Массив закрывающих тегов
     */
    private $closedTags = [];

    /**
     * @var int - Счетчик закрывающих тегов
     */
    private $closedTagsNumber = 1;

    /**
     * @var bool - Начался закрывающийся тег
     */
    private $startClosedTag = false;

    /**
     * @var array - Массив событий - например начался открывающийся тег, или начался контент внутри тегов
     */
    private $actions = [];

    /**
     * @var string - Шаблон для проверки допустимых символов внутри тега
     */
    private $parent = '/[a-zA-Z0-9]/';


    /**
     * Корректирует битые XML, проходя поочередно по каждому символу.
     *
     * Пример исходного битого XML - встречаются спецсимволы внутри тегов, плюс отсутствует один закрывающий тег:
     *
     * <inquiryreply>
     *     <inqcon,-/trolnum>25076563
     *     <inquiryperiod>последние 60 days</inquiryperiod>
     *     <inqpurpose>01</inqpurpose>
     * </inquiryreply>
     *
     * На выходе получаем:
     *
     * <inquiryreply>
     *     <inqcontrolnum>25076563</inqcontrolnum>
     *     <inquiryperiod>последние 60 days</inquiryperiod>
     *     <inqpurpose>01</inqpurpose>
     * </inquiryreply>
     *
     * Недостаток скрипта - достаточно большой расход памяти. Учитывайте это при проврки XML длиной >100 000 символов
     *
     * @param string $xml
     * @return string
     */
    public function replace(string $xml): string
    {
        $this->sourceXML = $xml;
        $this->stringToArray();
        $this->length = count($this->array);

        while ($this->i < $this->length) {

            if ($this->array[$this->i] === '<') {
                $this->startTag();
            }

            if ($this->startTag && $this->tagSymbolNumber === 2) {
                $this->defineTagType();
            }

            $this->saveTags();

            if ($this->array[$this->i] === '>') {
                $this->closedTag();
            }

            if (!$this->startTag && $this->array[$this->i] !== '>' && $this->array[$this->i] !== ' ' && $this->array[$this->i] !== PHP_EOL) {
                $this->startContent();
            }

            if ($this->startTag) {
                $this->tagSymbolNumber++;
            }

            $this->addReplaceXMLSymbol();

            $this->i++;
        }

        return $this->replaceXML;
    }

    /**
     * Разбирает строку на массив по одному символу, с учетом кириллицы в контексте
     */
    private function stringToArray(): void
    {
        $this->array = preg_split('//u', $this->sourceXML, null, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Совершает все необходимые действия при открытии тега
     */
    private function startTag(): void
    {
        $this->startTag = true;
        $this->startContent = false;
    }

    /**
     * Совершает все необходимые действия при закрытии тега
     */
    private function closedTag(): void
    {
        $this->startTag = false;
        $this->startOpenTag = false;
        $this->startClosedTag = false;
        $this->contentsNumber++;
        $this->tagNumber++;
        $this->openTagsNumber++;
        $this->closedTagsNumber++;
        $this->tagSymbolNumber = 1;
    }

    /**
     * Если начался контент - сохраняет его в специальном массиве
     */
    private function startContent(): void
    {
        if (empty($this->contents[$this->contentsNumber])) {
            $this->contents[$this->contentsNumber] = $this->array[$this->i];
            $this->actions[] = self::ACTION_CONTENT;
            $this->startContent = true;
        } else {
            $this->contents[$this->contentsNumber] .= $this->array[$this->i];
        }
    }

    /**
     * Проверяет, если сейчас обрабатывается тег - необходимо сохранить информацию о нем
     */
    private function saveTags(): void
    {
        if ($this->startTag && $this->array[$this->i] !== '<' && $this->array[$this->i] !== '>') {
            $this->saveTag();
        }

        if ($this->startOpenTag && $this->array[$this->i] !== '>' && preg_match($this->parent, $this->array[$this->i])) {
            $this->saveOpenTag();
        }

        if ($this->startClosedTag && $this->array[$this->i] !== '>') {
            $this->saveClosedTag();
        }
    }

    /**
     * Если обрабатывается какой-то тег - сохраняет его в общем массиве тегов. Эта информация нужна для дебага
     */
    private function saveTag(): void
    {
        if (empty($this->tags[$this->tagNumber])) {
            $this->tags[$this->tagNumber] = $this->array[$this->i];
        } else {
            $this->tags[$this->tagNumber] .= $this->array[$this->i];
        }
    }

    /**
     * Если обрабатывается открывающий тег - сохраняет его в отдельном массиве всех открытых тегов. Этот массив нужен
     * для того, чтобы создавать закрывающийся тег (если он вдруг отсутствует)
     */
    private function saveOpenTag(): void
    {
        if (empty($this->openTags[$this->openTagsNumber])) {
            $this->openTags[$this->openTagsNumber] = $this->array[$this->i];
        } else {
            $this->openTags[$this->openTagsNumber] .= $this->array[$this->i];
        }
    }

    /**
     * Если обрабатывается закрывающийся тег - сохраняет его в отдельном массиве всех закрывающих тегов.
     */
    private function saveClosedTag(): void
    {
        if (empty($this->closedTags[$this->closedTagsNumber])) {
            $this->closedTags[$this->closedTagsNumber] = $this->array[$this->i];
            $this->actions[] = self::ACTION_CLOSED_TAG;
        } else {
            $this->closedTags[$this->closedTagsNumber] .= $this->array[$this->i];
        }
    }

    /**
     * Когда обрабатывается второй символ тега - нужно проверить его тип (открывающийся или закрывающийся) и сделать
     * необходимые для каждого типа тегов действия (например, проверить, был ли закрывающийся тег)
     */
    private function defineTagType(): void
    {
        if ($this->array[$this->i] === '/') {
            $this->startClosedTag = true;
        } elseif ($this->array[$this->i] !== '/') {
            $this->startOpenTag = true;
            $this->checkClosedTag();
            $this->actions[] = self::ACTION_OPEN_TAG;
        }
    }

    /**
     * Проверяет наличие закрывающегося тега после контента. Если его нет - добавляет.
     *
     * Проверяется наличие закрывающегося тега когда уже открывается новый тег - соответственно, при добавлении
     * закрывающегося тега нужно удалить начало нового тега "<", а потом его вернуть.
     *
     * На данный момент проверяется только закрывающийся тег после контента. Но если контента не было вовсе - данный
     * скрипт ошибки не заметит.
     */
    private function checkClosedTag(): void
    {
        // TODO уйти от array_pop
        if (array_pop($this->actions) === self::ACTION_CONTENT) {

            // удаляем последний символ
            $this->replaceXML = mb_substr($this->replaceXML, 0, -1);

            // удаляем все пробелы и переносы строк из конца строки (ну и из начала, хотя их там не должно быть)
            $this->replaceXML = trim($this->replaceXML);

            // добавляем недостающий закрывающий тег
            // TODO уйти от array_pop
            $this->replaceXML .= '</'. array_pop($this->openTags) . '><';

            $this->actions[] = self::ACTION_CONTENT;
            $this->actions[] = self::ACTION_INJECTION_CLOSE_TAG;
        }
    }

    /**
     * Проверяет корректность символа и если все ок - добавляет его к итоговому XML-документу
     */
    private function addReplaceXMLSymbol(): void
    {
        if (!$this->startTag || $this->startClosedTag || $this->array[$this->i] === '<' || ($this->startTag && preg_match($this->parent, $this->array[$this->i]))) {
            $this->replaceXML .= $this->array[$this->i];
        }
    }

}

$replacer = new XMLReplacer();

$string = '<inquiryreply>
                <inqcon,-/trolnum>25076563 
                <inquiryperiod>последние 60 days</inquiryperiod>
                <inqpurpose>01</inqpurpose>
            </inquiryreply>';

$stringReplace = $replacer->replace($string);

var_dump($string);
var_dump($stringReplace);
