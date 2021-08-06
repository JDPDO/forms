<!--
 - @copyright Copyright (c) 2021 Jan Petersen <dev.jdpdo@outlook.de>
 -
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
    <div>
        <AppSettingsSection title="t('forms', 'Prerequisites')">
            <ListItem v-for="(question, index) in questions"
                :key="index"
                :title="question.text">
            </ListItem>
        </AppSettingsSection>
    </div>
</template>

<script>
import AppSettingsSection from '@nextcloud/vue/dist/Components/AppSettingsSection'
import ListItem from '@nextcloud/vue/dist/Components/ListItem'
import Multiselect from '@nextcloud/vue/dist/Components/Multiselect'

 export default {
    name: 'PrerequisteSelection',
    components: {
        AppSettingsSection,
        ListItem,
        Multiselect,
    },
    data() {
        return {
            selectedQuestions: [],
        }
    },
    props: {
        questions: {
            type: Array,
            required: true,
        },
        requiringQuestion: {
            type: Object,
            required: true,
        },
    },
 }
 </script>
 
 <style>
 
 </style>