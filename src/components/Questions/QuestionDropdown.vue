<!--
  - @copyright Copyright (c) 2020 John Molakvoæ <skjnldsv@protonmail.com>
  -
  - @author John Molakvoæ <skjnldsv@protonmail.com>
  - @author Jan Petersen <dev.jdpdo@outlook.de>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->

<template>
	<Question
		v-bind.sync="$attrs"
		:text="text"
		:is-required="isRequired"
		:edit.sync="edit"
		:read-only="readOnly"
		:max-question-length="maxStringLengths.questionText"
		:title-placeholder="answerType.titlePlaceholder"
		:warning-invalid="answerType.warningInvalid"
		:content-valid="contentValid"
		:shift-drag-handle="shiftDragHandle"
		@update:text="onTitleChange"
		@update:isRequired="onRequiredChange"
		@delete="onDelete">
		<select v-if="!edit"
			:id="text"
			:name="text"
			:multiple="isMultiple"
			:required="isRequired"
			class="question__content"
			@change="onChange">
			<option value="">
				{{ selectOptionPlaceholder }}
			</option>
			<option v-for="answer in options"
				:key="answer.id"
				:value="answer.id"
				:selected="isChecked(answer.id)">
				{{ answer.text }}
			</option>
		</select>

		<ol v-if="edit" class="question__content">
			<!-- Answer text input edit -->
			<AnswerInput v-for="(answer, index) in options"
				:key="index /* using index to keep the same vnode after new answer creation */"
				ref="input"
				:answer="answer"
				:index="index"
				:is-unique="!isMultiple"
				:is-dropdown="true"
				:max-option-length="maxStringLengths.optionText"
				@add="addNewEntry"
				@delete="deleteOption"
				@update:answer="updateAnswer" />

			<li v-if="!isLastEmpty || hasNoAnswer" class="question__item">
				<input
					:aria-label="t('forms', 'Add a new answer')"
					:placeholder="t('forms', 'Add a new answer')"
					class="question__input"
					:maxlength="maxStringLengths.optionText"
					minlength="1"
					type="text"
					@click="addNewEntry"
					@focus="addNewEntry">
			</li>
		</ol>
	</Question>
</template>

<script>
import AnswerInput from './AnswerInput'
import QuestionMixin from '../../mixins/QuestionMixin'
import QuestionWithOptionsMixin from '../../mixins/QuestionWithOptionsMixin'

export default {
	name: 'QuestionDropdown',

	components: {
		AnswerInput,
	},

	mixins: [
		QuestionMixin,
		QuestionWithOptionsMixin,
	],

	computed: {
		selectOptionPlaceholder() {
			if (this.readOnly) {
				return this.answerType.submitPlaceholder
			}
			return this.answerType.createPlaceholder
		},

		isMultiple() {
			// This can be extended if we want to include support for <select multiple>
			return false
		},
	},

	methods: {
		onChange(event) {
			// Get all selected options
			const answerIds = [...event.target.options]
				.filter(option => option.selected)
				.map(option => parseInt(option.value, 10))

			// Simple select
			if (!this.isMultiple) {
				this.$emit('update:values', [answerIds[0]])
				return
			}

			// Emit values and remove duplicates
			this.$emit('update:values', [...new Set(answerIds)])
		},
	},
}
</script>

<style lang="scss" scoped>
.question__content {
	display: flex;
	flex-direction: column;
}

.question__item {
	position: relative;
	display: inline-flex;
	min-height: 44px;
}

// Using type to have a higher order than the input styling of server
.question__input[type=text] {
	width: 100%;
	// Height 34px + 1px Border
	min-height: 35px;
	margin: 0;
	padding: 0 0;
	border: 0;
	border-bottom: 1px dotted var(--color-border-dark);
	border-radius: 0;
	font-size: 14px;
	position: relative;
}

// Fix display of select dropdown and adjust to Forms text
select.question__content {
	height: 44px;
	padding: 12px 0 12px 12px;
	font-size: 14px;
}
</style>
