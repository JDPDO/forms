/**
 * @copyright Copyright (c) 2021 Jan Petersen <dev.jdpdo@outlook.de>
 *
 * @author Jan Petersen <dev.jdpdo@outlook.de>
 *
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
 *
 */

import { generateOcsUrl } from '@nextcloud/router'
import { showError } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'

import GenRandomId from '../utils/GenRandomId'

export default {
	props: {
		/**
		 * The question list of answers
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
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
		 *
		 */
		 areNoneChecked() {
			return this.values.length === 0
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 */
		contentValid() {
			return this.answerType.validate(this)
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 */
		hasNoAnswer() {
			return this.options.length === 0
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 */
		isLastEmpty() {
			const value = this.options[this.options.length - 1]
			return value?.text?.trim().length === 0
		},

		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 */
		shiftDragHandle() {
			return this.edit && this.options.length !== 0 && !this.isLastEmpty
		},
	},

	watch: {
		/**
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {boolean} edit Èdit mode active?
		 */
		edit(edit) {
			// When leaving edit mode, filter and delete empty options
			if (!edit) {
				const options = this.options.filter(option => {
					if (!option.text) {
						this.deleteOptionFromDatabase(option)
						return false
					}
					return true
				})

				// update parent
				this.updateOptions(options)
			}
		},
	},

	methods: {
		/**
		 * Add a new empty answer locally
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 */
		addNewEntry() {
			// If entering from non-edit-mode (possible by click), activate edit-mode
			this.edit = true

			// Add local entry
			const options = this.options.slice()
			options.push({
				id: GenRandomId(),
				questionId: this.id,
				text: '',
				local: true,
			})

			// Update question
			this.updateOptions(options)

			this.$nextTick(() => {
				this.focusIndex(options.length - 1)
			})
		},

		/**
		 * Delete an option
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {number} id the options id
		 */
		deleteOption(id) {
			const options = this.options.slice()
			const optionIndex = options.findIndex(option => option.id === id)

			if (options.length === 1) {
				// Clear Text, but don't remove. Will be removed, when leaving edit-mode
				options[0].text = ''
			} else {
				// Remove entry
				const option = Object.assign({}, this.options[optionIndex])

				// delete locally
				options.splice(optionIndex, 1)

				// delete from Db
				this.deleteOptionFromDatabase(option)
			}

			// Update question
			this.updateOptions(options)

			this.$nextTick(() => {
				this.focusIndex(optionIndex - 1)
			})
		},

		/**
		 * Delete the option from Db in background.
		 * Restore option if delete not possible
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {object} option The option to delete
		 */
		deleteOptionFromDatabase(option) {
			const optionIndex = this.options.findIndex(opt => opt.id === option.id)

			if (!option.local) {
				// let's not await, deleting in background
				axios.delete(generateOcsUrl('apps/forms/api/v1.1/option/{id}', { id: option.id }))
					.catch(error => {
						showError(t('forms', 'There was an issue deleting this option'))
						console.error(error)
						// restore option
						this.restoreOption(option, optionIndex)
					})
			}
		},

		/**
		 * Focus the input matching the index
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {number} index the value index
		 */
		focusIndex(index) {
			const inputs = this.$refs.input
			if (inputs && inputs[index]) {
				const input = inputs[index]
				input.focus()
			}
		},

		/**
		 * Is the provided answer checked ?
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {number} id the answer id
		 * @return {boolean}
		 */
		isChecked(id) {
			return this.values.indexOf(id) > -1
		},

		/**
		 * Update the options
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {Array} options options to change
		 */
		updateOptions(options) {
			this.$emit('update:options', options)
		},

		/**
		 * Update an existing answer locally
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {string|number} id the answer id
		 * @param {object} answer the answer to update
		 */
		updateAnswer(id, answer) {
			const options = this.options.slice()
			const answerIndex = options.findIndex(option => option.id === id)
			options[answerIndex] = answer

			this.updateOptions(options)
		},

		/**
		 * Restore an option locally
		 *
		 * @author John Molakvoæ <skjnldsv@protonmail.com>
		 *
		 * @param {object} option the option
		 * @param {number} index the options index in this.options
		 */
		restoreOption(option, index) {
			const options = this.options.slice()
			options.splice(index, 0, option)

			this.updateOptions(options)
			this.focusIndex(index)
		},
	},
}
