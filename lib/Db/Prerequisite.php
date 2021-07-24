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

namespace OCA\Forms\Db;

use OCP\AppFramework\Db\Entity;
use OCP\DB\Types;

/**
 * Base type for conditional forms.
 *
 * @method string	getCondition()
 * @method integer	getConditionOptionId()
 * @method bool		getIsNot()
 * @method integer	getOptionId()
 */
class Prerequisite extends Entity {
	
	/**
	 * Condition of prequisite.
	 *
	 * @var string
	 */
	protected $condition;

	/**
	 * Option on which *condition* is applied.
	 *
	 * @var integer
	 */
	protected $conditionOptionId;

	public const CONDITION_TYPES = [
		'populated',
		'equals',
		'greater',
		'less',
	];

	/** @var bool */
	protected $isNot;

	/**
	 * Affected option.
	 *
	 * @var integer
	 */
	protected $optionId;

	/**
	 * Prerequisite constructor.
	 */
	public function __construct() {
		$this->addType('condition', Types::STRING);
		$this->addType('conditionOptionId', Types::INTEGER);
		$this->addType('isNot', Types::BOOLEAN);
		$this->addType('optionId', Types::INTEGER);
	}

	public function read(): array {
		return [
			'id' => $this->getId(),
			'condition' => $this->getCondition(),
			'conditionOptionId' => $this->getConditionOptionId(),
			'isNot' => $this->getIsNot(),
			'optionId' => $this->getOptionId(),
		];
	}
}
