/**
 * @copyright Copyright (c) 2021 Jan Petersen <dev.jdpdo@outlook.de>
 * @author Jan Petersen <dev.jdpdo@outlook.de>
 * @license AGPL-3.0-or-later
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
 */
export default {
	props: {
		/**
		 * The question list of answers
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 */
		options: {
			type: Array,
			required: true,
		},

		/**
		 * Form's questions
		 */
		questions: {
			type: Array,
			default() { return [] },
		},
	},
	computed: {
		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 */
		contentValid() {
			return this.answerType.validate(this)
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 */
		isLastEmpty() {
			const value = this.options[this.options.length - 1]
			return value?.text?.trim().length === 0
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 */
		hasNoAnswer() {
			return this.options.length === 0
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 */
		areNoneChecked() {
			return this.values.length === 0
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 */
		shiftDragHandle() {
			return this.edit && this.options.length !== 0 && !this.isLastEmpty
		},
	},
}
