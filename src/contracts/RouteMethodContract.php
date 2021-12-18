<?php


interface RouteMethodContract
{
    public function getPath(): string;

    public function getName(): string;

    public function getMethod(): string;
}
