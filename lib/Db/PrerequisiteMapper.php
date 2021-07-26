<?php
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

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

class PrerequisiteMapper extends QBMapper {

	/**
	 * PrerequisiteMapper constructor.
	 * @param IDBConnection $db
	 */
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'forms_v2_prerequisites', Prerequisite::class);
	}

	/**
	 * @param integer $id
	 * @return Prerequisite
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException More than one result
	 * @throws \OCP\AppFramework\Db\DoesNotExistException Not found
	 */
	public function findById(int $id): Prerequisite {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);

		return $this->findEntity($qb);
	}
	
	public function findByOption(int $optionId): array {
		$qb = $this->db->getQueryBuilder();

		$qb
			->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('option_id', $qb->createNamedParameter($optionId, IQueryBuilder::PARAM_INT))
			);

		return $this->findEntities($qb);
	}

	/**
	 * Delete every Prerequisite in relation to option.
	 * 
	 * @param integer $optionId
	 */
	public function deleteByOption(int $optionId): void {
		$qb = $this->db->getQueryBuilder();

		// Delete of affected options.
		$qb->delete($this->getTableName())
			->where(
				$qb->expr()->eq('option_id', $qb->createNamedParameter($optionId, IQueryBuilder::PARAM_INT))
			);

		// Delete of needed options.
		$qb->delete($this->getTableName())
			->where(
				$qb->expr()->eq('condition_option_id', $qb->createNamedParameter($optionId, IQueryBuilder::PARAM_INT))
			);

		$qb->execute();
	}
}
