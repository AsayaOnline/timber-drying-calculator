<?php
/**
 * Plugin Name: Timber Drying Calculator
 * Plugin URI: https://www.asayasculpture.com
 * Description: Professional wood drying time calculator for carvers and woodworkers
 * Version: 1.0
 * Author: Asaya
 * Author URI: https://www.asayasculpture.com/about
 * License: GPL v2 or later
 * Text Domain: timber-drying-calculator
 */


// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue styles
function tdc_enqueue_styles() {
    wp_register_style('tdc-styles', false);
    wp_enqueue_style('tdc-styles');
    
    $custom_css = "
    #timber-calculator {
        max-width: 600px;
        margin: 20px auto;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #ffffff;
        border: 1px solid #e1e4e8;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .tdc-header {
        background: #f6f8fa;
        padding: 20px 25px;
        border-bottom: 1px solid #e1e4e8;
    }
    
    .tdc-header h3 {
        margin: 0 0 5px 0;
        color: #24292e;
        font-size: 20px;
        font-weight: 600;
    }
    
    .tdc-header p {
        margin: 0;
        color: #586069;
        font-size: 14px;
    }
    
    #tdc-form {
        padding: 25px;
    }
    
    .tdc-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .tdc-input-group {
        margin-bottom: 20px;
    }
    
    .tdc-input-group label {
        display: block;
        margin-bottom: 6px;
        color: #24292e;
        font-size: 14px;
        font-weight: 600;
    }
    
    .tdc-input-wrapper {
        position: relative;
    }
    
    #timber-calculator input[type='number'],
    #timber-calculator select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e1e4e8;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        transition: border-color 0.2s;
    }
    
    #timber-calculator input[type='number']:focus,
    #timber-calculator select:focus {
        outline: none;
        border-color: #0366d6;
        box-shadow: 0 0 0 3px rgba(3, 102, 214, 0.1);
    }
    
    .tdc-unit {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #586069;
        font-size: 14px;
        pointer-events: none;
    }
    
    .tdc-radio-group {
        display: grid;
        gap: 12px;
    }
    
    .tdc-radio {
        display: flex;
        align-items: center;
        padding: 12px;
        border: 1px solid #e1e4e8;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .tdc-radio:hover {
        background: #f6f8fa;
    }
    
    .tdc-radio input[type='radio'] {
        margin-right: 10px;
    }
    
    .tdc-radio input[type='radio']:checked + span {
        color: #0366d6;
        font-weight: 600;
    }
    
    .tdc-button {
        width: 100%;
        padding: 12px 24px;
        background: #2ea44f;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .tdc-button:hover {
        background: #2c974b;
    }
    
    .tdc-result {
        margin-top: 25px;
        padding: 20px;
        background: #f6f8fa;
        border-radius: 6px;
        border: 1px solid #d1d5da;
    }
    
    .tdc-result-main {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .tdc-result-item {
        text-align: center;
        padding: 15px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e1e4e8;
    }
    
    .tdc-label {
        display: block;
        color: #586069;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .tdc-value {
        display: block;
        color: #0366d6;
        font-size: 24px;
        font-weight: 600;
    }
    
    .tdc-note {
        padding: 12px;
        background: #fff8c5;
        border: 1px solid #f5e06e;
        border-radius: 6px;
        font-size: 13px;
        line-height: 1.5;
        color: #735c0f;
    }
    
    .tdc-note strong {
        font-weight: 600;
    }
    
    @media (max-width: 480px) {
        .tdc-row,
        .tdc-result-main {
            grid-template-columns: 1fr;
        }
    }";
    
    wp_add_inline_style('tdc-styles', $custom_css);
}

// Main shortcode function
function tdc_calculator_shortcode() {
    tdc_enqueue_styles();
    
    ob_start();
    ?>
    <div id="timber-calculator">
        <div class="tdc-header">
            <h3>Timber Drying Calculator</h3>
            <p>Professional calculator for wood carvers and woodworkers</p>
        </div>
        
        <form id="tdc-form">
            <div class="tdc-row">
                <div class="tdc-input-group">
                    <label for="thickness">Wood Thickness</label>
                    <div class="tdc-input-wrapper">
                        <input type="number" id="thickness" min="0.5" max="50" value="5" step="0.5">
                        <span class="tdc-unit">cm</span>
                    </div>
                </div>
                
                <div class="tdc-input-group">
                    <label for="wood-type">Wood Species</label>
                    <select id="wood-type">
                        <optgroup label="Hardwoods">
                            <option value="1.2">Oak</option>
                            <option value="1.1">Ash</option>
                            <option value="1.0">Beech</option>
                            <option value="0.9">Cherry</option>
                            <option value="0.9">Walnut</option>
                            <option value="0.8">Maple</option>
                            <option value="0.7">Birch</option>
                        </optgroup>
                        <optgroup label="Softwoods">
                            <option value="0.7" selected>Basswood/Linden</option>
                            <option value="0.6">Pine</option>
                            <option value="0.5">Cedar</option>
                            <option value="0.5">Spruce</option>
                            <option value="0.4">Willow</option>
                        </optgroup>
                        <optgroup label="Exotic Woods">
                            <option value="1.3">Ebony</option>
                            <option value="1.2">Mahogany</option>
                            <option value="1.1">Teak</option>
                            <option value="0.8">Bamboo</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            
            <div class="tdc-input-group">
                <label for="drying-method">Drying Method</label>
                <div class="tdc-radio-group">
                    <label class="tdc-radio">
                        <input type="radio" name="method" value="1" checked>
                        <span>Air Drying (Natural)</span>
                    </label>
                    <label class="tdc-radio">
                        <input type="radio" name="method" value="0.3">
                        <span>Kiln Drying (Fast)</span>
                    </label>
                    <label class="tdc-radio">
                        <input type="radio" name="method" value="0.6">
                        <span>Dehumidifier</span>
                    </label>
                </div>
            </div>
            
            <button type="button" onclick="calculateDrying()" class="tdc-button">
                Calculate Drying Time
            </button>
            
            <div id="result" class="tdc-result" style="display:none;">
                <div class="tdc-result-main">
                    <div class="tdc-result-item">
                        <span class="tdc-label">Estimated Time:</span>
                        <span class="tdc-value" id="days-result"></span>
                    </div>
                    <div class="tdc-result-item">
                        <span class="tdc-label">Ready Date:</span>
                        <span class="tdc-value" id="date-result"></span>
                    </div>
                </div>
                
                <div class="tdc-note">
                    <strong>Note:</strong> These are estimates. Actual drying time depends on humidity, temperature, and air circulation. Always check moisture content (8-12% ideal) before carving.
                </div>
            </div>
        </form>
    </div>

    <script>
    function calculateDrying() {
        var thickness = parseFloat(document.getElementById('thickness').value);
        var woodType = parseFloat(document.getElementById('wood-type').value);
        var methodRadios = document.getElementsByName('method');
        var dryingMethod = 1;
        
        for(var i = 0; i < methodRadios.length; i++) {
            if(methodRadios[i].checked) {
                dryingMethod = parseFloat(methodRadios[i].value);
                break;
            }
        }
        
        var days = Math.round(thickness * thickness * woodType * dryingMethod);
        
        var readyDate = new Date();
        readyDate.setDate(readyDate.getDate() + days);
        var options = { year: 'numeric', month: 'short', day: 'numeric' };
        var formattedDate = readyDate.toLocaleDateString('en-US', options);
        
        document.getElementById('days-result').innerHTML = days + ' days';
        document.getElementById('date-result').innerHTML = formattedDate;
        document.getElementById('result').style.display = 'block';
    }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('timber_calculator', 'tdc_calculator_shortcode');