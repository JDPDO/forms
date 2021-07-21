<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2021 Jan Petersen <dev.jdpdo@outlook.de>
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

/**
 * @method integer getQuestionId()
 * @method void setQuestionId(integer $value)
 * @method string getText()
 * @method void setText(string $value)
 */
class Prerequisite extends Entity {
    
	/** @var string */
	protected $condition;

	public const CONDITION_TYPES = [
		'populated',
		'equals',
		'greater',
		'less',
	];

	/** @var bool */
	protected $isNot;

	/** 
	 * Condition of link to next (optional) prequisite.
	 * 
	 * @var string 
	 */
	protected $linkCondition;

	/** 
	 * Link next (optional) prequisite.
	 * 
	 * @var integer
	 */
	protected $linkedPrequsiteId;

	public const LINK_CONDITION_TYPES = [
		'and',
		'or',
	];

	/** @var integer */
	protected $optionId;

	/**
	 * Prerequisite constructor.
	 */
	public function __construct() {
		$this->addType('condition', 'string');
		$this->addType('isNot', 'bool');
		$this->addType('linkCondition', 'integer');
		$this->addType('linkedPrequsiteId', 'bool');
		$this->addType('optionId', 'integer');
	}

	public function read(): array {
		return [
			'id' => $this->getId(),
			'optionId' => $this->getQuestionId(),
			'condition' => $this->getCondition(),
			'isNot' => $this->getIsNot(),
			'linkCondition' => $this->getLinkCondition(),
			'optionId' => $this->getOptionId(),
		];
	}
}
