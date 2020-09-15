<?php
declare(strict_types=1);

namespace App\Infrastructure\Validators;

use JsonSchema\Validator;

class SchemaValidatorAdapter
{
    protected Validator $validator;

    protected string $schema;

    protected array $errors;

    /**
     * SchemaValidatorAdapter constructor.
     * @param string $schema
     */
    public function __construct(string $schema)
    {
        $this->validator = new Validator();
        $this->schema = $schema;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validate(array $data): bool
    {
        $data = (object) $data;

        $this->validator->validate($data, $this->getSchema());

        $this->errors = $this->validator->getErrors();

        return $this->validator->isValid();
    }

    /**
     * @return \stdClass
     */
    protected function getSchema(): \stdClass
    {
        return (object)['$ref' => 'file://' . realpath($this->schema)];
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
