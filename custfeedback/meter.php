<?php

/*
 * custfeedback-Admin module-Ctwo History_mobile
 *
 * @author ismath Khan  
 */

class meter extends gui {

    protected function content() {

        $this->importPlugin('thermometer/RGraph.common.core.js');
        $this->importPlugin('thermometer/RGraph.vprogress.js');

        return "<h1>A basic VProgress chart</h1>

    <canvas id='cvs' width='100' height='400'>[No canvas support]</canvas>
    
    <script>
        $(document).ready(function ()
        {
            var vprogress = new RGraph.VProgress('cvs', 89,100)
             .set('colors', ['Gradient(red:red)'])
                .set('tickmarks', 100)
                .set('numticks', 20)
                .set('gutter.right', 30)
                .set('margin', 5)
                .draw();
        })
    </script>

    <p>        
        <a href='./'>&laquo; Back</a>
    </p>";
    }

    

    protected function css() {
        return "
            table {
                border:1px solid #000000;
            }
            tr th{
               background:#0000ff;
               border-bottom:2px solid #000000;
               color:#fff;
            }
            tr td{
                background:#e0e0e0;
                border:2px solid #fff;
                text-align:center;
            }
            tr td:first-child{
                font-weight: bold;
                }";
    }

}
