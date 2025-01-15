<?php

namespace GraphQL_Starter\Classes;

use function esc_attr;
use function esc_html;
use function esc_textarea;
use function checked;

/**
 * Custom Field Renderer Class
 * 
 * Handles rendering of custom field inputs in the WordPress admin panel.
 *
 * Provides a standardized way to render different types of form inputs with proper
 * WordPress styling and security measures. Supports:
 * - inputs (text, email, url, tel, number, date, radio, checkbox)
 * - Textarea
 *
 * @see CustomField For field registration and configuration
 * @package GraphQL_Starter
 */
class CustomFieldRenderer
{
    /** @var array Field configuration including id, title, and input settings */
    private $args;

    /** @var mixed Current field value from post meta */
    private $value;

    /** @var array Input type specific configuration from CustomField::$input_configs */
    private $config;

    /**
     * @param array $args Field configuration array
     * @param mixed $value Current field value
     */
    public function __construct(array $args, mixed $value)
    {
        $this->args = $args;
        $this->value = $value;
        $this->config = $this->getInputConfig();
    }

    /**
     * Renders the complete field with styles and input element
     */
    public function render(): void
    {
        $this->renderStyles();
        $this->renderField();
    }

    /**
     * Gets input configuration based on field type
     *
     * @return array Default text input config if type not found
     */
    private function getInputConfig(): array
    {
        $type = $this->args['input_type']['type'] ?? 'text';
        return CustomField::$input_configs[$type] ?? CustomField::$input_configs['text'];
    }

    /**
     * Renders admin styles for all field types
     */
    private function renderStyles(): void
    {
        ?>
        <style>
            .custom-field-wrapper label {
                font-weight: 600;
                font-size: 13px;
                display: block;
                margin-bottom: 8px;
                color: #1e1e1e;
            }
            .custom-field-wrapper input[type="text"],
            .custom-field-wrapper input[type="number"],
            .custom-field-wrapper input[type="email"],
            .custom-field-wrapper input[type="url"],
            .custom-field-wrapper input[type="tel"],
            .custom-field-wrapper textarea {
                width: 100%;
                padding: 5px 10px !important;
                border: 1px solid #8c8f94;
                border-radius: 4px;
                font-size: 14px;
                line-height: 2;
                min-height: 30px;
                max-width: 1000px;  
            }
            .custom-field-wrapper input[type="number"] {
                width: 150px;
            }
            .custom-field-wrapper textarea {
                min-height: 80px;
                height: 80px;
                resize: vertical;
            }
            .custom-field-wrapper input:focus,
            .custom-field-wrapper textarea:focus {
                border-color: #2271b1;
                box-shadow: 0 0 0 1px #2271b1;
                outline: none;
            }
            .custom-field-wrapper .radio-group,
            .custom-field-wrapper .checkbox-group {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-top: 4px;
            }
            .custom-field-wrapper .radio-option,
            .custom-field-wrapper .checkbox-option {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .custom-field-wrapper .radio-option input[type="radio"],
            .custom-field-wrapper .checkbox-option input[type="checkbox"] {
                width: 16px;
                height: 16px;
                margin: 0;
            }
            .custom-field-wrapper .radio-option label,
            .custom-field-wrapper .checkbox-option label {
                font-weight: normal;
                margin: 0;
                cursor: pointer;
                font-size: 13px;
            }
        </style>
        <?php
    }

    /**
     * Renders the field container and appropriate input type
     *
     * Dynamically calls the appropriate render method based on field type:
     * - Constructs method name from input type (e.g., 'text' -> 'renderTextField')
     * - Falls back to text input if method doesn't exist
     */
    private function renderField(): void
    {
        ?>
        <div class="custom-field-wrapper">
            <?php
            $method = 'render' . ucfirst($this->config['type']) . 'Field';
            if (method_exists($this, $method)) {
                $this->$method();
            } else {
                $this->renderTextField();
            }
            ?>
        </div>
        <?php
    }

    /**
     * Renders a basic input field (text, email, url, tel, number)
     */
    private function renderTextField(): void
    {
        ?>
        <label for="<?php echo esc_attr($this->args['id']); ?>">
            <?php echo esc_html($this->args['title']); ?>
        </label>
        <input
            type="<?php echo esc_attr($this->config['attributes']['type']); ?>"
            id="<?php echo esc_attr($this->args['id']); ?>"
            name="<?php echo esc_attr($this->args['id']); ?>"
            value="<?php echo esc_attr($this->value); ?>"
            <?php $this->renderAttributes(); ?>
        />
        <?php
    }

    /**
     * Renders a textarea for multi-line content
     */
    private function renderTextareaField(): void
    {
        ?>
        <label for="<?php echo esc_attr($this->args['id']); ?>">
            <?php echo esc_html($this->args['title']); ?>
        </label>
        <textarea
            id="<?php echo esc_attr($this->args['id']); ?>"
            name="<?php echo esc_attr($this->args['id']); ?>"
            <?php $this->renderAttributes(); ?>
        ><?php echo esc_textarea($this->value); ?></textarea>
        <?php
    }

    /**
     * Renders a group of radio buttons
     */
    private function renderRadioField(): void
    {
        if (empty($this->args['options'])) {
            return;
        }
        ?>
        <label><?php echo esc_html($this->args['title']); ?></label>
        <div class="radio-group">
            <?php foreach ($this->args['options'] as $option_value => $option_label): ?>
                <div class="radio-option">
                    <input
                        type="radio"
                        id="<?php echo esc_attr($this->args['id'] . '_' . $option_value); ?>"
                        name="<?php echo esc_attr($this->args['id']); ?>"
                        value="<?php echo esc_attr($option_value); ?>"
                        <?php checked($this->value, $option_value); ?>
                    />
                    <label for="<?php echo esc_attr($this->args['id'] . '_' . $option_value); ?>">
                        <?php echo esc_html($option_label); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Renders a group of checkboxes for multiple selections
     */
    private function renderCheckboxField(): void
    {
        if (empty($this->args['options'])) {
            return;
        }
        $checked_values = !empty($this->value) ? (array)$this->value : [];
        ?>
        <label><?php echo esc_html($this->args['title']); ?></label>
        <div class="checkbox-group">
            <?php foreach ($this->args['options'] as $option_value => $option_label): ?>
                <div class="checkbox-option">
                    <input
                        type="checkbox"
                        id="<?php echo esc_attr($this->args['id'] . '_' . $option_value); ?>"
                        name="<?php echo esc_attr($this->args['id']); ?>[]"
                        value="<?php echo esc_attr($option_value); ?>"
                        <?php checked(in_array($option_value, $checked_values)); ?>
                    />
                    <label for="<?php echo esc_attr($this->args['id'] . '_' . $option_value); ?>">
                        <?php echo esc_html($option_label); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Renders HTML attributes for input fields
     */
    private function renderAttributes(): void
    {
        $attributes = array_merge(
            $this->config['attributes'] ?? [],
            $this->args['input_type']['attributes'] ?? []
        );

        foreach ($attributes as $attr => $value) {
            if ($value !== null && $value !== '') {
                echo esc_attr($attr) . '="' . esc_attr($value) . '" ';
            }
        }
    }
} 