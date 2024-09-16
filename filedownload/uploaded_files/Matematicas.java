/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 *
 * @author Fer
 */
public class Matematicas {

    public static int absoluto(int a) {
        if (a < 0) {
            a = -a;
        }
        return a;
    }

    public static double absoluto(double a) {
        if (a < 0) {
            a = -a;
        }
        return a;
    }

    public static int maximo(int a, int b) {
        if (a > b) {
            return a;
        }
        return b;
    }

    public static double maximo(double a, double b) {
        if (a >= b) {
            return a;
        }
        return b;
    }

    public static int minimo(int a, int b) {
        if (a > b) {
            return b;
        }
        return a;
    }

    public static double minimo(double a, double b) {
        if (a > b) {
            return b;
        }
        return a;
    }

    public static int redondear(double a) {

        if (a - (int) a >= 0.5) {
            return (int) a + 1;
        }
        return (int) a;
    }

    public static int redondearAlza(double a) {
        //6.1 -> 7
        //6.0 -> 6.0 

        if (a != (int) a) {
            return (int) a + 1;
        }
        return (int) a;

    }

    public static int redondearBaja(double a) {

        return (int) a;
    }

    public static int potencia(int base, int exponente) {
        int resultado = 1;

        for (int i = 0; i < exponente; i++) {
            resultado = resultado * base;
        }
        return resultado;
    }

    public static int aleatorio(int fin) {

        return (int) (Math.random() * (fin + 1));
    }

    public static int aleatorio(int inicio, int fin) {

        return (int) (Math.random() * (fin - inicio)) + inicio;
    }
}
