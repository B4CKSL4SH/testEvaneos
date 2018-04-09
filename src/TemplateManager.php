<?php

class TemplateManager
{
    /**
     * @param Template $tpl
     * @param array $data
     * @return Template
     */
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (empty($tpl)) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->setSubject($this->computeText($replaced->getSubject(), $data));
        $replaced->setContent($this->computeText($replaced->getContent(), $data));

        return $replaced;
    }

    /**
     * @param string $text
     * @param string $key
     * @param string $pattern
     * @return bool
     */
    private function checkPatternExists($text, $key, $pattern)
    {
        return strpos($text, '[' . $key . ':' . $pattern . ']') !== false;
    }

    /**
     * @param string $text
     * @param string $key
     * @param string $pattern
     * @param string $replace
     * @return string
     */
    private function checkAndReplace($text, $key, $pattern, $replace)
    {
        if (!$this->checkPatternExists($text, $key, $pattern)) {
            return $text;
        }
        return str_replace('[' . $key . ':' . $pattern . ']', $replace, $text);
    }

    /**
     * @param string $text
     * @param array $data
     * @return string
     */
    private function computeText($text, array $data)
    {
        if (empty($data['quote']) || !($data['quote'] instanceof Quote)) {
            return $text;
        }
        if (!empty($data['user']) && $data['user'] instanceof User) {
            $user = $data['user'];
        } else {
            $user = ApplicationContext::getInstance()->getCurrentUser();
        }
        $quote = QuoteRepository::getInstance()->getById($data['quote']->getId());
        $site = SiteRepository::getInstance()->getById($data['quote']->getSiteId());
        $destination = DestinationRepository::getInstance()->getById($data['quote']->getDestinationId());

        // Replace Quote Patterns
        $text = $this->checkAndReplace($text, 'quote', 'summary_html', Quote::renderHtml($quote));
        $text = $this->checkAndReplace($text, 'quote', 'summary', Quote::renderText($quote));
        $text = $this->checkAndReplace($text, 'quote', 'destination_name', $destination->getCountryName());
        if (!empty($destination)) {
            $replace = $site->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $quote->getId();
            $text = $this->checkAndReplace($text, 'quote', 'destination_link', $replace);
        } else {
            $text = str_replace('[quote:destination_link]', '', $text);
        }
        // Replace User Patterns
        $text = $this->checkAndReplace($text, 'user', 'first_name', ucfirst(mb_strtolower($user->getFirstname())));
        return $text;
    }
}
