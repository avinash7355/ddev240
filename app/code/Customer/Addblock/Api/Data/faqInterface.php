<?php
namespace Customer\Addblock\Api\Data;

interface faqInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID       = 'id';
    const QUESTION = 'question';
    const ANSWER   = 'answer';
    


    /**
     * Get Title
     *
     * @return string|null
     */
    public function getQuestion();

    /**
     * Get Content
     *
     * @return string|null
     */
    public function getAnswer();

    /**
     * Get Created At
     *
     * @return string|null
     */
    
    public function getId();

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setQuestion($question);

    /**
     * Set Content
     *
     * @param string $content
     * @return $this
     */
    public function setAnswer($answer);

   

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);
}