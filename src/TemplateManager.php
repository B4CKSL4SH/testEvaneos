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
        return strpos($text, '['.$key.':'.$pattern.']') !== false;
    }

    /**
     * @param string $text
     * @param array $data
     * @return string
     */
    private function computeText($text, array $data)
    {
        if (!empty($data['quote']) && $data['quote'] instanceof Quote) {
            $quote = $data['quote'];
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->getId());
            $usefulObject = SiteRepository::getInstance()->getById($quote->getSiteId());
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->getDestinationId());

            if ($this->checkPatternExists($text, 'quote', 'destination_link')) {
                $destination = DestinationRepository::getInstance()->getById($quote->getDestinationId());
                if (!empty($destination)) {
                    $text = str_replace(
                        '[quote:destination_link]',
                        $usefulObject->getUrl() . '/' . $destination->getCountryName() . '/quote/' . $_quoteFromRepository->getId(),
                        $text
                    );
                } else {
                    $text = str_replace('[quote:destination_link]', '', $text);
                }
            }

            if ($this->checkPatternExists($text, 'quote', 'summary_html')) {
                $text = str_replace(
                    '[quote:summary_html]',
                    Quote::renderHtml($_quoteFromRepository),
                    $text
                );
            }
            if ($this->checkPatternExists($text, 'quote', 'summary')) {
                $text = str_replace(
                    '[quote:summary]',
                    Quote::renderText($_quoteFromRepository),
                    $text
                );
            }
            if ($this->checkPatternExists($text, 'quote', 'destination_name'))
                $text = str_replace(
                    '[quote:destination_name]',
                    $destinationOfQuote->getCountryName(),
                    $text
                );
        }

        $_user = !empty($data['user']) && $data['user'] instanceof User ? $data['user'] : ApplicationContext::getInstance()->getCurrentUser();
        if ($this->checkPatternExists($text, 'user', 'first_name')) {
            $text = str_replace(
                '[user:first_name]',
                ucfirst(mb_strtolower($_user->getFirstname())),
                $text
            );
        }
        return $text;
    }
}
