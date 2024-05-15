<?php

\Illuminate\Support\Facades\Schedule::command("app:donator-check")->everySixHours();
\Illuminate\Support\Facades\Schedule::command("app:verify-whitelist")->daily();
