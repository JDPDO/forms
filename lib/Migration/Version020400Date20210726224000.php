<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2021 Jan Petersen <dev.jdpdo@outlook.de>
 *
 * @author Jan Petersen <dev.jdpdo@outlook.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Forms\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version020400Date20210726224000 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		$tableName = 'forms_v2_prerequisites';
		if (!$schema->hasTable($tableName)) {
            $table = $schema->createTable($tableName);
            $table->addColumn('id', Types::INTEGER, [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('condition', Types::INTEGER, [
                'notnull' => true,
            ]);
            $table->addColumn('condition_option_id', Types::INTEGER, [
                'notnull' => true,
            ]);
            $table->addColumn('is_not', Types::BOOLEAN, [
                'notnull' => false,
            ]);
            $table->addColumn('option_id', Types::INTEGER, [
                'notnull' => true,
            ]);
            
            return $schema;
        }

		return null;
	}

    public function description(): string
    {
        return <<<DESC
Adding prerequisites type. 
DESC;
    }
}