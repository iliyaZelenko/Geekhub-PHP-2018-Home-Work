<?php

namespace App\Twig;

use Twig\Template as Tem;

# я не знаю как его переопределить Twig\Template,
# думал поместить в src/Twig/Template.php в надежде что композер поймет, но не работает
class Template extends Tem
{
    /**
     * Returns the template name.
     *
     * @return string The template name
     */
    public function getTemplateName()
    {
        // TODO: Implement getTemplateName() method.
        return 'Tem';
    }

    /**
     * Returns debug information about the template.
     *
     * @return array Debug information
     *
     * @internal
     */
    public function getDebugInfo()
    {
        // TODO: Implement getDebugInfo() method.
    }

    /**
     * Auto-generated method to display the template with the given context.
     *
     * @param array $context An array of parameters to pass to the template
     * @param array $blocks An array of blocks to pass to the template
     */
    protected function doDisplay(array $context, array $blocks = array())
    {
        dump($context, $blocks);
        // TODO: Implement doDisplay() method.
    }
}
