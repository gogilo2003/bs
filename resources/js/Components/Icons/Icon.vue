<script setup lang="ts">
import { computed, ref } from 'vue';
import ActivityLog from './ActivityLog.vue'
import Add from './Add.vue'
import Address from './Address.vue'
import ArrowLeft from './ArrowLeft.vue'
import Certificate from './Certificate.vue'
import Chart from './Chart.vue'
import ChevronCircleBack from './ChevronCircleBack.vue'
import ChevronCircleForward from './ChevronCircleForward.vue'
import ChevronDown from './ChevronDown.vue'
import ChevronUp from './ChevronUp.vue'
import Close from './Close.vue'
import Dashboard from './Dashboard.vue'
import Delete from './Delete.vue'
import Edit from './Edit.vue'
import Ellipsis from './Ellipsis.vue'
import Email from './Email.vue'
import Event from './Event.vue'
import Facebook from './Facebook.vue'
import Instagram from './Instagram.vue'
import Linkedin from './Linkedin.vue'
import Location from './Location.vue'
import Menu from './Menu.vue'
import Message from './Message.vue'
import Money from './Money.vue'
import People from './People.vue'
import PeopleCircle from './PeopleCircle.vue'
import Phone from './Phone.vue'
import Refresh from './Refresh.vue'
import Search from './Search.vue'
import Settings from './Settings.vue'
import Twitter from './Twitter.vue'
import Youtube from './Youtube.vue'
import Picture from './Picture.vue'
import PersonDetails from './PersonDetails.vue'
import Person from './Person.vue'
import Female from './Female.vue'
import Male from './Male.vue'
import Community from './Community.vue'
import Team from './Team.vue'
import Excel from './Excel.vue'
import Pdf from './Pdf.vue'
import Calendar from './Calendar.vue'
import Checkmark from './Checkmark.vue'
import Download from './Download.vue'
import Upload from './Upload.vue'
import Done from './Done.vue'
import Teacher from './Teacher.vue'
import BoardMember from './BoardMember.vue';
import Gear from './Gear.vue';

import 'primeicons/primeicons.css'

const props = defineProps({ type: String })

const icon = computed(() => {
    if (props.type == 'facebook') {
        return Facebook;
    } else if (props.type == 'twitter') {
        return Twitter;
    } else if (props.type == 'linkedin') {
        return Linkedin;
    } else if (props.type == 'instagram') {
        return Instagram;
    } else if (props.type == 'youtube') {
        return Youtube;
    } else if (props.type == 'phone') {
        return Phone;
    } else if (props.type == 'email') {
        return Email;
    } else if (props.type == 'address') {
        return Address;
    } else if (props.type == 'location') {
        return Location;
    } else if (props.type == 'close') {
        return Close;
    } else if (props.type == 'activity-log') {
        return ActivityLog;
    } else if (props.type == 'add') {
        return Add;
    } else if (props.type == 'arrow-left') {
        return ArrowLeft;
    } else if (props.type == 'certificate') {
        return Certificate;
    } else if (props.type == 'chart') {
        return Chart;
    } else if (props.type == 'chevron-circle-back') {
        return ChevronCircleBack;
    } else if (props.type == 'chevron-circle-forward') {
        return ChevronCircleForward;
    } else if (props.type == 'chevron-down') {
        return ChevronDown;
    } else if (props.type == 'chevron-up') {
        return ChevronUp;
    } else if (props.type == 'dashboard') {
        return Dashboard;
    } else if (props.type == 'delete') {
        return Delete;
    } else if (props.type == 'edit') {
        return Edit;
    } else if (props.type == 'ellipsis') {
        return Ellipsis;
    } else if (props.type == 'event') {
        return Event;
    } else if (props.type == 'menu') {
        return Menu;
    } else if (props.type == 'message') {
        return Message;
    } else if (props.type == 'money') {
        return Money;
    } else if (props.type == 'people') {
        return People;
    } else if (props.type == 'people-circle') {
        return PeopleCircle;
    } else if (props.type == 'refresh') {
        return Refresh;
    } else if (props.type == 'search') {
        return Search;
    } else if (props.type == 'settings') {
        return Settings;
    } else if (props.type == 'picture') {
        return Picture;
    } else if (props.type == 'person-details') {
        return PersonDetails;
    } else if (props.type == 'person') {
        return Person;
    } else if (props.type == 'female') {
        return Female;
    } else if (props.type == 'male') {
        return Male;
    } else if (props.type == 'community') {
        return Community;
    } else if (props.type == 'team') {
        return Team;
    } else if (props.type == 'excel') {
        return Excel;
    } else if (props.type == 'pdf') {
        return Pdf;
    } else if (props.type == 'calendar') {
        return Calendar;
    } else if (props.type == 'checkmark') {
        return Checkmark;
    } else if (props.type == 'download') {
        return Download;
    } else if (props.type == 'upload') {
        return Upload;
    } else if (props.type == 'done') {
        return Done;
    } else if (props.type == 'teacher') {
        return Teacher;
    } else if (props.type == 'board_member') {
        return BoardMember;
    }
    else if (props.type == 'gear') {
        return Gear
    }
    classes.value = `pi ${props.type}`
    return 'span';
})

const classes = ref("")
</script>
<template>
    <component :is="icon" :class="classes" />
</template>
