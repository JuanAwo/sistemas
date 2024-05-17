<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'mpdf/vendor/autoload.php';

use Mpdf\Mpdf;

class Mhtml2pdf {

    var $html;
    var $path;
    var $filename;
    var $paper_size;
    var $orientation;

    // /**
    //  * Constructor
    //  *
    //  * @access	public
    //  * @param	array	initialization parameters
    //  */
    // function Html2pdf($params = array())
    // {
    //     $this->CI =& get_instance();
    //
    //     if (count($params) > 0)
    //     {
    //         $this->initialize($params);
    //     }
    //
    //     log_message('debug', 'PDF Class Initialized');
    //
    // }

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
    function initialize($params)
	{
        $this->clear();
		if (count($params) > 0)
        {
            foreach ($params as $key => $value)
            {
                if (isset($this->$key))
                {
                    $this->$key = $value;
                }
            }
        }
	}

	// --------------------------------------------------------------------

	/**
	 * Set html
	 *
	 * @access	public
	 * @return	void
	 */
	function html($html = NULL)
	{
        $this->html = $html;
	}

	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */
	function folder($path)
	{
        $this->path = $path;
	}

	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */
	function filename($filename)
	{
        $this->filename = $filename;
	}

	// --------------------------------------------------------------------


	/**
	 * Set paper
	 *
	 * @access	public
	 * @return	void
	 */
	function paper($paper_size = NULL, $orientation = NULL)
	{
        $this->paper_size = $paper_size;
        $this->orientation = $orientation;
	}

	// --------------------------------------------------------------------


	/**
	 * Create PDF
	 *
	 * @access	public
	 * @return	void
	 */
	function create($mode = 'view', $css = NULL)
	{
        $mpdf = new Mpdf();
        // $mpdf->baseScript = 1;
        $mpdf->autoScriptToLang = true;
        // $mpdf->autoVietnamese = true;
        $mpdf->autoLangToFont = true;
        // $mpdf->autoArabic = true;

        $mpdf->AddPage('P', // L - landscape, P - portrait
                    'A4', '', '', '',
                    12, // margin_left
                    12, // margin right
                    10, // margin top
                    10, // margin bottom
                    5, // margin header
                    5); // margin footer
        // if( !empty($css) ) {
        //     $mpdf->WriteHTML($css, 1);
        // }
        if(!empty($css)) {
            $mpdf->WriteHTML($css, 1);
        }

        $mpdf->WriteHTML($this->html);
        if($mode == 'view') {
            $mpdf->Output($this->filename . '.pdf','I'); // D - Force download, I - View in explorer
        } elseif ($mode == 'save') {
            $mpdf->Output($this->path.$this->filename . '.pdf','F');
            return $this->path.$this->filename . '.pdf';
        } elseif ($mode == 'download') {
            $mpdf->Output($this->filename . '.pdf','D');
        }
	}

}

/* End of file Mpdf.php */
