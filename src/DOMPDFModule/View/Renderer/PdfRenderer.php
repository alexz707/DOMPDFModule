<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Raymond J. Kolbe <rkolbe@gmail.com>
 * @copyright Copyright (c) 2012 University of Maine, 2016 Raymond J. Kolbe
 * @license	http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace DOMPDFModule\View\Renderer;

use Dompdf\Dompdf;
use DOMPDFModule\View\Model\PdfModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface as Renderer;
use Zend\View\Resolver\ResolverInterface as Resolver;

class PdfRenderer implements Renderer
{
    /**
     * @var Dompdf
     */
    private $dompdf;

    /**
     * @var PhpRenderer
     */
    private $htmlRenderer;

    public function setHtmlRenderer(PhpRenderer $renderer)
    {
        $this->htmlRenderer = $renderer;

        return $this;
    }

    public function getHtmlRenderer()
    {
        return $this->htmlRenderer;
    }

    public function setEngine(Dompdf $dompdf)
    {
        $this->dompdf = $dompdf;

        return $this;
    }

    public function getEngine()
    {
        return $this->dompdf;
    }

    /**
     * Renders values as a PDF
     *
     * @param string|PdfModel $nameOrModel
     * @param  null|array|\ArrayAccess Values to use during rendering
     *
     * @return string The script output.
     * @throws \Zend\View\Exception\InvalidArgumentException
     * @throws \Zend\View\Exception\DomainException
     * @throws \Zend\View\Exception\RuntimeException
     * @internal param Model|string $name The script/resource process, or a view model
     */
    public function render($nameOrModel, $values = null)
    {
        $html = $this->getHtmlRenderer()->render($nameOrModel);

        $paperSize = $nameOrModel->getOption('paperSize');
        $paperOrientation = $nameOrModel->getOption('paperOrientation');
        $basePath = $nameOrModel->getOption('basePath');

        $pdf = $this->getEngine();
        $pdf->set_paper($paperSize, $paperOrientation);
        $pdf->set_base_path($basePath);

        $pdf->load_html($html);
        $pdf->render();

        return $pdf->output();
    }

    public function setResolver(Resolver $resolver)
    {
        //not using it anywhere
    }
}
