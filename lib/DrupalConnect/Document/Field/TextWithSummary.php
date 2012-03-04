<?php
namespace DrupalConnect\Document\Field;

/**
 * This field stores long text in the database along with optional summary text.
 */
class TextWithSummary extends Text
{

    /**
     * @var string
     */
    protected $_summary;

    /**
     * @var string
     */
    protected $_safeSummary;

    /**
     * Sample field data
     *      array(
     *          'value' => 'text_value',
     *          'summary' => 'text_value', // optional
     *          'format' => 'plain_text', // optional
     *          'safe_value' => 'safe_value', // optional
     *          'safe_summary' => 'safe_summary', // optional
     *      )
     *
     * @param array|null $data Field Data
     */
    public function __construct(array $data = null)
    {
        parent::__construct($data);

        // if data set
        if ($data)
        {
            if (isset($data['summary']))
            {
                $this->setSummary($data['summary']);
            }
            if (isset($data['safe_summary']))
            {
                $this->setSafeSummary($data['safe_summary']);
            }
        }
    }

    /**
     * @param string $summary
     * @return TextWithSummary
     */
    public function setSummary($summary)
    {
        $this->_summary = $summary;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->_summary;
    }


    /**
     * @param string $safeSummary
     * @return TextWithSummary
     */
    public function setSafeSummary($safeSummary)
    {
        $this->_safeSummary = $safeSummary;
        return $this;
    }

    /**
     * @return string
     */
    public function getSafeSummary()
    {
        return $this->_safeSummary;
    }

}
