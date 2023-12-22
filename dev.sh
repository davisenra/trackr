#!/bin/bash

laravel_cmd="cd ./server && php artisan serve --host api.trackr.test"
vite_cmd="cd ./spa && npm run dev -- --host trackr.test"

tmux new-session -d -s trackr
tmux split-window -v
tmux send-keys -t trackr.0 "$laravel_cmd" C-m
tmux send-keys -t trackr.1 "$vite_cmd" C-m
tmux attach-session -t trackr
