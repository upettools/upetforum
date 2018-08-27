<?php
/**
 * This file is part of Top Stats plugin for MyBB.
 * Copyright (C) 2010-2013 baszaR & LukasAMD & Supryk
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */ 

/**
 * Disallow direct access to this file for security reasons
 * 
 */
if (!defined("IN_MYBB")) exit;

$l['topStats_Name'] = "Statystyki Top";
$l['topStats_NameDesc'] = "Dodaje statystyki TOP, w panelu bocznym. Ilość wyświetlanych tematów/użytkowników ustalamy w panelu.";
 
$l['topStats'] = "Statystyki Top";
$l['topStats_Desc'] = "Ustawienia pluginu Statystyki TOP.";

$l['topStats_Status_All'] = "Włącz/wyłącz wszystkie statystyki.";
$l['topStats_Status_AllDesc'] = "Ta opcja pozwala na globalne wyłączenie wszystkich statystyk.";

$l['topStats_Status_LastThreads'] = "Najnowsze tematy";
$l['topStats_Status_LastThreadsDesc'] = "Wyświetlanie widgetu z najnowszymi tematami.";
$l['topStats_IgnoreForums_LastThreads'] = "Ignorowane fora dla najnowszych tematów";
$l['topStats_IgnoreForums_LastThreadsDesc'] = "Wpisz FID for które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Tematy z tych for nie będą wyświetlane.";
$l['topStats_Limit_LastThreads'] = "Limit najnowszych tematów";
$l['topStats_Limit_LastThreadsDesc'] = "";

$l['topStats_Status_LastActiveThreads'] = "Ostatnio aktywne tematy";
$l['topStats_Status_LastActiveThreadsDesc'] = "Wyświetlanie widgetu z ostatnio aktywnymi tematami.";
$l['topStats_IgnoreForums_LastActiveThreads'] = "Ignorowane fora dla ostatnio aktywnych tematów";
$l['topStats_IgnoreForums_LastActiveThreadsDesc'] = "Wpisz FID for które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Tematy z tych for nie będą wyświetlane.";
$l['topStats_Limit_LastActiveThreads'] = "Limit ostatnio aktywnych tematów";
$l['topStats_Limit_LastActiveThreadsDesc'] = "";

$l['topStats_Status_MostViews'] = "Najczęściej wyświetlane tematy";
$l['topStats_Status_MostViewsDesc'] = "Wyświetlanie widgetu z najczęściej wyświetlanymi tematami.";
$l['topStats_IgnoreForums_MostViews'] = "Ignorowane fora dla najczęśćiej wyświetlanych tematów";
$l['topStats_IgnoreForums_MostViewsDesc'] = "Wpisz FID for które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Tematy z tych for nie będą wyświetlane.";
$l['topStats_Limit_MostViews'] = "Limit najczęśćiej wyświetlanych tematów";
$l['topStats_Limit_MostViewsDesc'] = "";
 
$l['topStats_Status_Posters'] = "Użytkownicy z największą ilością postów";
$l['topStats_Status_PostersDesc'] = "Wyświetlanie widgetu z listą użytkowników o największej ilości napisanych postów.";
$l['topStats_IgnoreGroups_Posters'] = "Ignorowane grupy dla użytkowników (posty)";
$l['topStats_IgnoreGroups_PostersDesc'] = "Wpisz GID grup które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Użytkownicy z tych grup nie będą wyświetlani.";
$l['topStats_Limit_Posters'] = "Limit użytkowników (posty)";
$l['topStats_Limit_PostersDesc'] = "";

$l['topStats_Status_Reputation'] = "Użytkownicy z największą reputacją";
$l['topStats_Status_ReputationDesc'] = "Wyświetlanie widgetu z listą użytkowników o największej reputacji.";
$l['topStats_IgnoreGroups_Reputation'] = "Ignorowane grupy dla użytkowników (reputacja)";
$l['topStats_IgnoreGroups_ReputationDesc'] = "Wpisz GID grup które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Użytkownicy z tych grup nie będą wyświetlani.";
$l['topStats_Limit_Reputation'] = "Limit użytkowników (reputacja)";
$l['topStats_Limit_ReputationDesc'] = "";

$l['topStats_Status_Referrals'] = "Użytkownicy z największą ilością poleceń";
$l['topStats_Status_ReferralsDesc'] = "Wyświetlanie widgetu z listą użytkowników o największej ilości poleconych użytkowników.";
$l['topStats_IgnoreGroups_Referrals'] = "Ignorowane grupy dla użytkowników (polecający)";
$l['topStats_IgnoreGroups_ReferralsDesc'] = "Wpisz GID grup które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Użytkownicy z tych grup nie będą wyświetlani.";
$l['topStats_Limit_Referrals'] = "Limit użytkowników (polecający)";
$l['topStats_Limit_ReferralsDesc'] = "";

$l['topStats_Status_TimeOnline'] = "Użytkownicy będący najdłużej online";
$l['topStats_Status_TimeOnlineDesc'] = "Wyświetlanie widgetu z listą użytkowników będących najdłużej online.";
$l['topStats_IgnoreGroups_TimeOnline'] = "Ignorowane grupy dla użytkowników (online)";
$l['topStats_IgnoreGroups_TimeOnlineDesc'] = "Wpisz GID grup które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Użytkownicy z tych grup nie będą wyświetlani.";
$l['topStats_Limit_TimeOnline'] = "Limit użytkowników (online)";
$l['topStats_Limit_TimeOnlineDesc'] = "";

$l['topStats_Status_NewestUsers'] = "Najnowsi użytkownicy";
$l['topStats_Status_NewestUsersDesc'] = "Wyświetlanie widgetu z listą najnowszych użytkowników.";
$l['topStats_IgnoreGroups_NewestUsers'] = "Ignorowane grupy dla użytkowników (najnowsi)";
$l['topStats_IgnoreGroups_NewestUsersDesc'] = "Wpisz GID grup które nie mają być brane pod uwage, jeśli więcej niż jedno oddziel przecinkami. Użytkownicy z tych grup nie będą wyświetlani.";
$l['topStats_Limit_NewestUsers'] = "Limit użytkowników (najnowsi)";
$l['topStats_Limit_NewestUsersDesc'] = "";

$l['topStats_Status_Moderators'] = "Najaktywniejsi moderatorzy";
$l['topStats_Status_ModeratorsDesc'] = "Wyświetlanie widgetu z listą najaktywniejszych moderatorów.";
$l['topStats_Limit_Moderators'] = "Limit użytkowników (najaktywniejsi moderatorzy)";
$l['topStats_Limit_ModeratorsDesc'] = "";

$l['topStats_Status_Avatar'] = "Wyświetlanie awatarów";
$l['topStats_Status_AvatarDesc'] = "Wyświetlanie awatarów użytowników i autorów tematów w  statystykach.";

$l['topStats_AvatarWidth'] = "Szerokość i wysokość avatara";
$l['topStats_AvatarWidthDesc'] = "Ustaw szerokość i wysokość awatara np.: 32x32.";
