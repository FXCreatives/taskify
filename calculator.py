def get_calc_info():

    print(f"    --- Calculator---    ")
    print(f"Note: The supported operations of this calculator are; addition, subtraction, division "
          f"\nmultiplication, exponential and squared root of numbers.")
    operators = input("Enter the type of operation you are performing: ")

    if operators in ["addition", "Addition", "+", "Add", "add"]:
        print(f"--- Performing Addition of Numbers ---")
        user_inp = int(input("Enter the count of numbers you want to add (for example "
                             f"\nif I want to add 4 numbers (12,13,48,12) I will type 4): "))
        sum_num = 0
        while user_inp > 0:
            num = float(input("Enter the numbers you want to sum up (one after the other): "))
            sum_num = sum_num + num
            user_inp -= 1

        print(f"Answer: {sum_num}")
    elif operators in ["subtraction", "Subtraction", "-", "subtract", "Substract", "Subtract"]:
        print(f"--- Performing Subtraction of Numbers ---")
        user_inp = int(input("Enter the count of numbers you want to subtract (for example "
                             f"\nif I want to subtract 4 numbers from the first, e.g. 12-13-48-12, I will type 4): "))
        sub_num = float(input("Enter the first number: "))
        user_inp -= 1
        while user_inp > 0:
            num = float(input("Enter the next number: "))
            sub_num = sub_num - num
            user_inp -= 1

        print(f"Answer: {sub_num}")
    elif operators in ["multiplication", "Multiplication", "*", "multiply", "Multiply", "times"]:
        print(f"--- Performing Multiplication of Numbers ---")
        user_inp = int(input("Enter the count of numbers you want to multiply (for example "
                             f"\nif I want to multiply 4 numbers (12,13,48,12), I will type 4): "))
        mul_num = 1.0
        while user_inp > 0:
            num = float(input("Enter the numbers you want to multiply (one after the other): "))
            mul_num = mul_num * num
            user_inp -= 1

        print(f"Answer: {mul_num}")
    elif operators in ["division", "Division", "/", "divide", "Divide"]:
        print(f"--- Performing Division of Numbers ---")
        div_num1 = float(input("Enter the numerator: "))
        div_num2 = float(input("Enter the denominator: "))
        if div_num2 == 0:
            print(f"Division can't be performed with a zero (0) denominator.")
        else:
            answer = div_num1 / div_num2
            print(f"Answer: {answer}")
    elif operators in ["exponential", "Exponential", "exp", "Exponent", "exponent"]:
        print(f"--- Performing Exponential of Numbers ---")
        expo_num1 = float(input("Enter the base number: "))
        expo_num2 = float(input("Enter the exponent: "))
        answer = expo_num1 ** expo_num2
        print(f"Answer: {answer}")
    elif operators in ["square root", "squared root", "sqrt", "Sqrt", "SQRT", "Squared root",
                        "Square root", "root", "Root"]:
        print(f"--- Performing Squared Root of Numbers ---")
        sqrt_num1 = float(input("Enter the number to find the root of: "))
        sqrt_num2 = float(input("Enter the root degree (e.g. 2 for square root): "))
        if sqrt_num2 == 0:
            print("Root degree cannot be zero.")
        else:
            answer = sqrt_num1 ** (1.0 / sqrt_num2)
            print(f"Answer: {answer}")
    else:
        print(f"Invalid operation entered")

get_calc_info()
